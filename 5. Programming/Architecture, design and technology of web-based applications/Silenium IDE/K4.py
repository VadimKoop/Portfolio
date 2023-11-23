import os, subprocess
from flask import Flask, render_template

app = Flask(__name__)


@app.route("/", methods=['GET', 'POST'])
def main_page():
    if request.method == 'GET':
        return render_template('index.html')
    if request.method == "POST":
        date = request.form["date"]
        return render_template('testresults.html', date=date, time=time, often=often)


@app.route("/results")
def get_results():
    outputs = []
    for test in range(0, 5):
        path = os.path.dirname(os.path.realpath(__file__)) + "/Tests/"
        p = subprocess.Popen("python " + path + "testcase" + str(test+1) + ".py", stderr=subprocess.PIPE)
        out, err = p.communicate()
    return jsonify(outputs)


if __name__ == '__main__':
    app.run()
