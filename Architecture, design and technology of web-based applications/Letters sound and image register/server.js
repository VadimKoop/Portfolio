const CFG = {
  HOST: 'localhost',
  PORT: 80,
  DB_NAME: 'k5db',
  DB_PORT: 27017
};

const Mongo = require('mongodb');
const Express = require('express');
const BodyParser = require('body-parser');

const app = Express();
app.use(BodyParser.json());
app.use(BodyParser.urlencoded({extended: true}));
app.use('/', Express.static('static'));

const database = Mongo.MongoClient;
database.connect(`mongodb://${CFG.HOST}:${CFG.DB_PORT}/${CFG.DB_NAME}`, function(err, db) {
  if(err) {
    console.error(err);
    return;
  }

  function dbFilesCollection(cb) {
    db.createCollection('files', function(err, collc) {
      if(err) {
        console.error(err);
        res.status(500).send({});
        cb(null);
        return;
      }
      collc.createIndex({text: 1});
      cb(collc);
    });
  }

  var server = app.listen(CFG.PORT, function() {

    app.post('/suggest', function(req, res) {
      console.log('Requesting search suggestions');

      dbFilesCollection(function(collc) {
        collc.find().toArray().then(function(docs) {
          var data = [];
          for(var i = 0; i < docs.length; i++) {
            data.push(docs[i].text.toLowerCase());
          }
          res.status(200).send(data);
        });
      });
    });

    app.post('/details', function(req, res) {
      console.log(`Requesting file: '${req.body.text}'`);

      dbFilesCollection(function(collc) {
        collc.findOne(
          {text: req.body.text.toLowerCase()},
          {text: 1, file: 1, _id: 0}
        ).then(function(doc) {
          if(doc == null) {
            console.log('File not found');
            res.status(400).send({});
          } else {
            console.log('File found!');
            res.status(200).send(doc);
          }
        });
      });
    });

    app.post('/upload', function(req, res) {
      console.log(`Uploading file: '${req.body.text}': ${req.body.file}`);

      dbFilesCollection(function(collc) {
        collc.deleteMany({text: req.body.text.toLowerCase()});

        collc.insert({
          text: req.body.text.toLowerCase(),
          file: req.body.file
        });

        res.status(203).send({});
      });
    });

  });
});
