import sqlite3
from contextlib import closing
from flask import Flask, request, session, g, redirect, url_for, \
    abort, render_template, flash, jsonify
from os.path import expanduser

# configuration
DATABASE = expanduser("~") + '/BAHInterlopers.db'
DEBUG = True
SECRET_KEY = 'development key'
USERNAME = 'admin'
PASSWORD = 'default'

app = Flask(__name__)
app.config.from_object(__name__)
# app.config.from_envvar('FLASKR_SETTINGS', silent=True)

@app.route('/')
def show_entries():
    return render_template('main_page.html')


@app.route('/map/<mapid>')
def mapping(mapid):
    if mapid is None:
        return redirect(url_for(map_select))
    return render_template('mapper.html', mapid=mapid)

@app.route('/add-marker', methods=['POST'])
def add_marker():
    if not session.get('logged_in'):
        abort(401)
    cur = g.db.execute('insert into markers (mapid, title, latitude, longitude) values (?, ?, ?, ?)',
                 [request.form['mapid'], request.form['title'], request.form['lat'], request.form['long']])
    g.db.commit()
    flash("Marker added")
    lastid = cur.lastrowid
    item = jsonify(itemid=lastid)
    return item

@app.route('/delete-marker', methods=['POST'])
def delete_marker():
    if not session.get('logged_in'):
        abort(401)
    marker_id = request.form['markerid']
    g.db.execute('DELETE FROM markers WHERE id = ?', (str(marker_id),))
    g.db.commit()
    flash("Marker removed")
    return "Success"

@app.route('/get-markers', methods=['POST'])
def get_markers():
    if not session.get('logged_in'):
        abort(401)
    cur = g.db.execute('SELECT * FROM markers WHERE mapId=?', unicode(request.form['mapid']))
    entries = []
    for row in cur.fetchall():
        d = {'id' : row[0],
             'mapId' : row[1],
             'title' : row[2],
             'latitude' : row[3],
             'longitude' : row[4]}
        entries.append(d)
    retVal = jsonify(items=entries)
    return retVal

@app.route('/solvers')
def solvers():
    return render_template('solvers.html')


@app.route('/login', methods=['GET', 'POST'])
def login():
    error = None
    if request.method == 'POST':
        if request.form['username'] != app.config['USERNAME']:
            error = 'Invalid username'
        elif request.form['password'] != app.config['PASSWORD']:
            error = 'Invalid password'
        else:
            session['logged_in'] = True
            flash('You were logged in')
            return redirect(url_for('show_entries'))
    return render_template('login.html', error=error)


@app.route('/logout')
def logout():
    session.pop('logged_in', None)
    flash('You were logged out')
    return redirect(url_for('show_entries'))


@app.route('/mapselect')
def map_select():
    cur = g.db.execute('select * from maps')
    entries = [row for row in cur.fetchall()]
    return render_template('map_selector.html', entries=entries)

@app.route('/add-map', methods=['POST'])
def add_entry():
    if not session.get('logged_in'):
        abort(401)
    g.db.execute('insert into maps (title) values (?)',
                 [request.form['title']])
    g.db.commit()
    flash("New map was successfully created")
    return redirect(url_for('map_select'))


@app.before_request
def before_request():
    g.db = connect_db()


@app.teardown_request
def teardown_request(exception):
    db = getattr(g, 'db', None)
    if db is not None:
        db.close()


def init_db():
    with closing(connect_db()) as db:
        with app.open_resource('schema.sql', mode='r') as f:
            db.cursor().executescript(f.read())
        db.commit()

    print "SUCCESS"


def connect_db():
    return sqlite3.connect(app.config['DATABASE'])


if __name__ == '__main__':
    app.run()
