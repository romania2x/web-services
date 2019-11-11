# VASILE v0.1.2

## Setup

```bash
docker-compose up -d
```


### `>> /etc/hosts`

```
127.0.0.1 public.vasile mongo.vasile kibana.vasile rethinkdb.vasile
```


### Utilities:

* [Kong](http://public.vasile)
* [Mongo Express](http://mongo.vasile/) `admin/pass`
* [Neo4j](http://localhost:7474/browser/) `test/test`
* [Kibana](http://kibana.vasile/)
* [RethinkDB Dashboard](http://rethinkdb.vasile/)


### Preload data

#### Siruta

```bash
#source_type = data_gov_ro.[siruta|companies|postal_codes|...] => check App\Entity\OpenData\Source
#start a consumer in a shell
bin/console messenger:consume

#in another shell execute the following commands
bin/console data-gov-ro:load resources/datasets/datasets.csv #to load datasets urls from csv
bin/console data-gov-ro:refresh [source_type] #to re-download resources
bin/console data-gov-ro:process [source_type] #to process sources and import data
```
