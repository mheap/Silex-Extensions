#!/bin/sh
git clone https://github.com/nrk/predis  		      vendor/predis
git clone --recursive https://github.com/doctrine/mongodb 		  vendor/mongodb
git clone https://github.com/fabpot/Twig 	 	      vendor/twig
git clone https://github.com/mandango/mandango        vendor/mandango
git clone https://github.com/knplabs/KnpMarkdownBundle   vendor/knplabs-markdown/Knp/Bundle/MarkdownBundle
git clone https://github.com/kriswallsmith/assetic    vendor/assetic
git clone https://github.com/fate/Gravatar-php 	      vendor/gravatar-php
git clone https://github.com/fate/embedly-php         vendor/embedly-php