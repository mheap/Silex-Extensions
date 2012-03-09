# installing memcached extension
curl -s http://pecl.php.net/get/memcached-2.0.1.tgz > memcached-2.0.1.tgz
tar -xzf memcached-2.0.1.tgz
sh -c "cd memcached-2.0.1 && phpize && ./configure && make && sudo make install"
echo "extension=memcached.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

curl -s http://pecl.php.net/get/memcache-2.2.6.tgz > memcache-2.2.6.tgz
tar -xzf memcache-2.2.6.tgz
sh -c "cd memcache-2.2.6 && phpize && ./configure && make && sudo make install"
echo "extension=memcache.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

# installing mongo extension
curl -s http://pecl.php.net/get/mongo-1.2.9.tgz > mongo-1.2.9.tgz
tar -xzf mongo-1.2.9.tgz
sh -c "cd mongo-1.2.9 && phpize && ./configure && make && sudo make install"
echo "extension=mongo.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

wget silex-project.org/get/silex.phar

wget http://getcomposer.org/composer.phar
php composer.phar install