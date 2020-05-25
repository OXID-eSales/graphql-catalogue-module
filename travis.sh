#!/bin/bash

TRAVIS_BUILD_DIR=$1;
TRAVIS_BRANCH=$2;
DOCS_BUILD_BRANCH=$3;
TRAVIS_PHP_VERSION=$4
DEPENDENCIES=$5

if [[ "$TRAVIS_BRANCH" != "$DOCS_BUILD_BRANCH" ]]; then
  echo "We're not on the $DOCS_BUILD_BRANCH branch."
  # analyze current branch and react accordingly
  exit 0
fi

if [[ "$TRAVIS_PHP_VERSION" = "7.2" && "$DEPENDENCIES" = "" ]]; then
  echo "Documentation will be generated."
else
  echo "We only build and deploy the docs for specified PHP version to save time."
  exit 0
fi

sudo sed -e 's|utf8_unicode_ci|latin1_general_ci|g; s|utf8|latin1|g' --in-place /etc/mysql/my.cnf
sudo service mysql restart
mkdir $TRAVIS_BUILD_DIR/deploy && mkdir $TRAVIS_BUILD_DIR/source && mkdir $TRAVIS_BUILD_DIR/source/tmp
mkdir $TRAVIS_BUILD_DIR/var && mkdir $TRAVIS_BUILD_DIR/var/configuration
mkdir $TRAVIS_BUILD_DIR/var/configuration/shops && touch $TRAVIS_BUILD_DIR/var/configuration/shops/1.yaml
echo -e "imports:" > $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
echo -n "    - { resource:" >> $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
echo  " SET_PATH_HERE_A }" >> $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
echo -n "    - { resource:" >> $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
echo  " SET_PATH_HERE_B }" >> $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
sed -i "s|\SET_PATH_HERE_A|\'$TRAVIS_BUILD_DIR/services.yaml'|" $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
sed -i "s|\SET_PATH_HERE_B|\'$TRAVIS_BUILD_DIR/vendor/oxid-esales/graphql-base/services.yaml'|" $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
less $TRAVIS_BUILD_DIR/var/configuration/configurable_services.yaml
cd $TRAVIS_BUILD_DIR/vendor/oxid-esales/oxideshop-ce
cp source/config.inc.php.dist source/config.inc.php
sed -i 's|<dbHost>|localhost|; s|<dbName>|oxideshop|; s|<dbUser>|root|; s|<dbPwd>||; s|<sShopURL>|http://localhost|; s|<sShopDir>|'$TRAVIS_BUILD_DIR'/source|; s|<sCompileDir>|'$TRAVIS_BUILD_DIR'/source/tmp|; s|$this->iDebug = 0|$this->iDebug = 1|' source/config.inc.php
sed -i "s|\$this->edition = ''|\$this->edition = 'CE'|" source/config.inc.php
sed -i "s|\$this->sCompileDir = ''|\$this->sCompileDir = '$TRAVIS_BUILD_DIR/source/tmp'|" source/config.inc.php
sed -i "s|\INSTALLATION_ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR|\AUTOLOAD_PATH|" source/bootstrap.php
cd $TRAVIS_BUILD_DIR && pwd
php -S localhost:8080 &
php graphdocs.php
graphdoc -e http://localhost:8080/graphdocs.php -o ./deploy/schema
test -e ./deploy/schema && echo file exists || echo file not found
