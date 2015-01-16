drop table if exists maps;
create table maps (
  id integer primary key autoincrement,
  title text not null
);

DROP TABLE IF EXISTS markers;
CREATE TABLE markers (
  id integer PRIMARY KEY AUTOINCREMENT,
  mapId integer,
  title text,
  latitude double,
  longitude double,
  FOREIGN KEY(mapId) REFERENCES maps(id)
);
