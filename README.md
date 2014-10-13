bmf
===

Basic Micro Framework


Description

A basic framework that allows you to build dynamic presentation websites


Instructions

Redirect all requests to index.php since it's a RESTful API.

Apache: 
Rename all htaccess.txt files to .htaccess and make sure ModRewrite is enabled

Nginx:

	# Redirect all URL parameters to index.php
	location / {
		try_files $uri $uri/ /index.php?$args;
		# include maintenance.conf;
	}
	
	# Deny access to all .htaccess files
    location ~ /\.ht {
        deny all;
    }

	# Deny access to template and libs folder
	location ~ /(templates|libs) {
		deny all;
		return 404;
	}


