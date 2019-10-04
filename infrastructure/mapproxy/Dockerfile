FROM debian:stretch
RUN apt-get update && apt-get install -y python-pip python-pil python-yaml libproj12 && pip install MapProxy
ADD mapproxy.yaml .
CMD mapproxy-util serve-develop mapproxy.yaml -b 0.0.0.0:80
