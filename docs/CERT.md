# HTTPS certificate

## Let's encrypt

How to generate a self signed certificate with lets encrypt

There are few options hot to let verify certbot, that your domain is correct. We will show you method with webroot, because, this method does not require to shutdown nginx or apache server. Other method require port 80 to be available.

Run in linux shell

```
certbot certonly -a webroot --webroot-path=/var/www/path/to/public/directory -d yourdomain.example
```

> Make sure, that you public folder contains `.well-known` directory and that it's allowed for access by all in nginx or apache virtual host configuration

Then you can uncomment `SSL` section in you virtual host and restart nginx / apache.