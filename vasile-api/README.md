Vasile API

```shell script
bin/console osm:load-country europe romania
osm2pgsql -H 127.0.0.1 -U postgres -W --create --database osm /tmp/europe-romania.osm.pbf

bin/console fos:elastica:populate --index planet_osm_polygon
bin/console fos:elastica:populate --index planet_osm_line
bin/console fos:elastica:populate --index planet_osm_point
bin/console fos:elastica:populate --index planet_osm_way
```
