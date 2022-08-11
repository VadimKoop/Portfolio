var express     =  require('express');
var bodyParser  =  require('body-parser');
var mysql       =  require('mysql');


/* MySQL database connection */

const SERVER_MODE = 'Production';

const CONNECTION_CONFIGURATION = {
    Test: {
        host        : 'localhost',
        port        :  3306,
        user        : 'root',
        password    : '',
        database    : 'forest'
    },
    Production: {
        host        : 'localhost',
        port        :  3306,
        user        : 'st2014',
        password    : 'progress',
        database    : 'st2014'
    }
};

const TABLE_NAMES = {
    Test: {
        Animals     : 'animals',
        Sightings   : 'sightings'
    },
    Production: {
        Animals     : 't164755_k3_animals',
        Sightings   : 't164755_k3_sightings'
    }
};

const LISTEN_PORT = {
    Test       : 80,
    Production : 7555
};

var conn = mysql.createConnection(
    CONNECTION_CONFIGURATION[SERVER_MODE]
);

const TABLE_ANIMALS   = TABLE_NAMES[SERVER_MODE].Animals;
const TABLE_SIGHTINGS = TABLE_NAMES[SERVER_MODE].Sightings;

conn.connect();


/* Server */

var app = express();

app.use(bodyParser.json());                             // Support application/json
app.use(bodyParser.urlencoded({extended: true}));       // Support application/x-www-form-urlencoded

app.use('/', express.static('static'));                 // Serve static website content


/* Server-side validation */

// Animal name must be 2 to 64 symbols long, start with a letter, allowed symbols are ' and -
var regexName     = /\w[ \w\-\']{1,63}/;
// Species name must be 2 to 64 symbols long, start with a letter, allowed symbols are ' and -
var regexSpecies  = /\w[ \w\-\']{1,63}/;
// Location name must be 2 to 64 symbols long, start with a letter, allowed symbol are numbers, ' and -
var regexLocation = /\w[ \w\-\'0-9]{1,63}/;
// Datetime format is FullYear-Month-Day Hours:Minutes:Seconds
var regexDatetime = /\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}:\d{2}/;

function isValidString(str, pattern) {
    return (typeof str == 'string' && str.match(pattern));
}

function isValidClientData(data, expectedFields) {
    for(var i = 0; i < expectedFields.length; i++) {
        switch(expectedFields[i]) {
            case 'name':
                if(!isValidString(data.name, regexName)) return false;
                break;
            case 'species':
                if(!isValidString(data.species, regexSpecies)) return false;
                break;
            case 'location':
                if(!isValidString(data.location, regexLocation)) return false;
                break;
            case 'datetime':
                if(!isValidString(data.datetime, regexDatetime)) return false;
                break;
        }
    }
    return true;
}


/* Animal CRUD+List operations
 *
 * Create  :  POST    /animals/:name
 * Read    :  GET     /animals/:name
 * Update  :  PUT     /animals/:name
 * Delete  :  DELETE  /animals/:name
 * List    :  POST    /animals
 */

// Create
app.post('/animals/:name', function(req, res) {

    // Validate data before proceeding
    if (!isValidClientData(req.params, ['name']) || !isValidClientData(req.body, ['species'])) {
        // Reply with 400 Bad Request
        res.status(400).send();
        return;
    }

    // Check whether an animal with this name already exists
    conn.query(
        'select name from ' + TABLE_ANIMALS + ' where name = ?;',
        [req.params.name],
        function(err, rows) {
            // Check for MySQL errors
            if(err == null) {
                // Check if there are any animals with the provided name
                if(rows.length == 0) {
                    // There are no such animals, proceed to add animal
                    conn.query(
                        'insert into ' + TABLE_ANIMALS + ' (name, species) values (?, ?);',
                        [req.params.name, req.body.species],
                        function(err) {
                            // Check for MySQL errors
                            if(err == null) {
                                // Successfully added the animal
                                // Reply with 204 No Content
                                res.status(204).send();
                            } else {
                                // MySQL error
                                // Reply with 500 Internal Server Error
                                res.status(500).send(err);
                            }
                        }
                    );
                } else {
                    // Animal with such name already exists
                    //TODO: Handle attempt to add existing animal
                    res.status(204).send();
                }
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});

// Read
app.get('/animals/:name', function(req, res) {

    // Data is only being read, no validation required

    // Attempt to retrieve the animal information
    conn.query(
        'select name, species from ' + TABLE_ANIMALS + ' where name = ?;',
        [req.params.name],
        function(err, rows) {
            // Check for MySQL errors
            if(err === null) {
                // Because animal name is unique, we are expecting exactly 1 row
                if(rows.length == 1) {
                    // Found animal information
                    // Reply with 200 OK + animal info
                    res.status(200).send(rows[0]);
                } else {
                    // There are no animals with such name
                    // Reply with 204 No Content
                    res.status(204).send();
                }
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});

// Update
app.put('/animals/:name', function(req, res) {

    // Validate data before proceeding
    if (!isValidClientData(req.params, ['name']) || !isValidClientData(req.body, ['name', 'species'])) {
        // Reply with 400 Bad Request
        res.status(400).send();
        return;
    }

    // Attempt to update animal information (will only happen if it exists)
    conn.query(
        'update ' + TABLE_ANIMALS + ' set name = ?, species = ? where name = ?;',
        [req.body.name, req.body.species, req.params.name],
        function(err) {
            // Check for MySQL errors
            if(err == null) {
                // Animal information is updated
                // Reply with 204 No Content
                res.status(204).send();
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});

// Delete
app.delete('/animals/:name', function(req, res) {

    // Data is only being deleted, no validation required

    // Attempt to delete the animal (will only happen if it exists)
    conn.query(
        'delete from ' + TABLE_ANIMALS + ' where name = ?;',
        [req.params.name],
        function(err) {
            // Check for MySQL errors
            if(err == null) {
                // Animal removed
                // Reply with 204 No Content
                res.status(204).send();
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});

// List
app.post('/animals', function(req, res) {

    // Data is only being read, no validation required

    // Check whether the species criteria is provided

    if(typeof req.body.species != 'undefined') {
        conn.query(
            'select name, species from ' + TABLE_ANIMALS + ' where species = ?;',
            [req.body.species],
            function(err, rows) {
                // Check for MySQL errors
                if(err == null) {
                    if(rows.length > 0) {
                        // Found some animal information
                        // Reply with 200 OK + list of animal info
                        res.status(200).send(rows);
                    } else {
                        // Nothing found
                        // Reply with 204 No Content
                        res.status(204).send();
                    }
                } else {
                    // MySQL error
                    // Reply with 500 Internal Server Error
                    res.status(500).send(err);
                }
            }
        );
    }
});


/* Sighting CRUD+List operations
 *
 * Create  :  POST    /sightings/:id
 * Read    :  GET     /sightings/:id
 * Update  :  PUT     /sightings/:id
 * Delete  :  DELETE  /sightings/:id
 * List    :  POST    /sightings
 */


// Create
app.post('/sightings/:id', function(req, res) {

    // Validate data before proceeding
    if (!isValidClientData(req.body, ['name', 'location', 'datetime'])) {
        // Reply with 400 Bad Request
        res.status(400).send();
        return;
    }

    // Add sighting
    conn.query(
        'insert into ' + TABLE_SIGHTINGS + ' (name, location, datetime) values (?, ?, ?);',
        [req.body.name, req.body.location, req.body.datetime],
        function(err) {
            // Check for MySQL errors
            if(err == null) {
                // Successfully added the sighting
                // Reply with 204 No Content
                res.status(204).send();
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );

});


// Read
app.get('/sightings/:id', function(req, res) {

    // Data is only being read, no validation required

    // Attempt to retrieve the sighting information
    conn.query(
        'select id, name, location, datetime from ' + TABLE_SIGHTINGS + ' where id = ?;',
        [req.params.id],
        function(err, rows) {
            // Check for MySQL errors
            if(err === null) {
                // Because sighting ID is unique, we are expecting exactly 1 row
                if(rows.length == 1) {
                    // Found sighting information
                    // Reply with 200 OK + sighting info
                    res.status(200).send(rows[0]);
                } else {
                    // There are no sightings with such name
                    // Reply with 204 No Content
                    res.status(204).send();
                }
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});


// Update
app.put('/sightings/:id', function(req, res) {

    // Validate data before proceeding
    if (!isValidClientData(req.body, ['location', 'datetime'])) {
        // Reply with 400 Bad Request
        res.status(400).send();
        return;
    }

    // Attempt to update sighting information (will only happen if it exists)
    conn.query(
        'update ' + TABLE_SIGHTINGS + ' set location = ?, datetime = ? where id = ?;',
        [req.body.location, req.body.datetime, parseInt(req.params.id)],
        function(err) {
            // Check for MySQL errors
            if(err == null) {
                // Sighting information is updated
                // Reply with 204 No Content
                res.status(204).send();
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});


// Delete
app.delete('/sightings/:id', function(req, res) {

    // Data is only being deleted, no validation required

    // Attempt to delete the sighting (will only happen if it exists)
    conn.query(
        'delete from ' + TABLE_SIGHTINGS + ' where id = ?;',
        [parseInt(req.params.id)],
        function(err) {
            // Check for MySQL errors
            if(err == null) {
                // Sighting removed
                // Reply with 204 No Content
                res.status(204).send();
            } else {
                // MySQL error
                // Reply with 500 Internal Server Error
                res.status(500).send(err);
            }
        }
    );
});


// List
app.post('/sightings', function(req, res) {

    // Data is only being read, no validation required

    // Switch between two modes of operation depending on request:

    if(typeof req.body.name != 'undefined') { // Searching by name
        conn.query(
            'select id, name, location, datetime from ' + TABLE_SIGHTINGS + ' where name = ? order by datetime asc;',
            [req.body.name],
            function(err, rows) {
                if(err == null) {
                    if(rows.length > 0) {
                        // Found some animal information
                        // Reply with 200 OK + list of sighting info
                        res.status(200).send(rows);
                    } else {
                        // Nothing found
                        // Reply with 204 No Content
                        res.status(204).send();
                    }
                } else {
                    // If MySQL query failed for some reason, reply with 500 Internal Server Error
                    res.status(500).send(err);
                }
            }
        );
    } else if(typeof req.body.location != 'undefined') { // Searching by location
        conn.query(
            'select id, name, location, datetime from ' + TABLE_SIGHTINGS + ' where location = ? order by datetime asc;',
            [req.body.location],
            function(err, rows) {
                if(err == null) {
                    if(rows.length > 0) {
                        // Found some animal information
                        // Reply with 200 OK + list of sighting info
                        res.status(200).send(rows);
                    } else {
                        // Nothing found
                        // Reply with 204 No Content
                        res.status(204).send();
                    }
                } else {
                    // If MySQL query failed for some reason, reply with 500 Internal Server Error
                    res.status(500).send(err);
                }
            }
        );
    }
});


// Launch

app.listen(LISTEN_PORT[SERVER_MODE], function() {});
