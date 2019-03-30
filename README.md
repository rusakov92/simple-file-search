# Simple File Search
---
This app simply finds files by searching their content.
# Instalation
---
*Empty*
# Usage
---
*Empty*
# Demo
---
To see the demo please first install [docker](https://www.docker.com/) on
your machine, once you are done please follow the guide below:

Clone this repository in a preferred location, now we need to build our
docker image by changing the directory into the cloned repository and then
running the docker build command to build an image.
```bash
cd /path/to/SimpleFileSearch
docker build -t simplefilesearch .
```
Now we need to start our container using docker-compose. Note that the
container port is set to be 127.0.0.1:8080 in docker-compose.yaml file.
If that port is already in use please change the port to something else
that it's free.
```bash
docker-compose up
```
See the demo here [127.0.0.1:8080](http://127.0.0.1:8080/)
 
