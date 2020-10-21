#!/bin/bash
bold=$(tput bold)
normal=$(tput sgr0)

echo "${bold}Init..."
echo "${bold}1/12 - Remove ./node_modules directory${normal}"
rm -rf "node_modules"
echo "${bold}2/12 - Remove ./vendor directory${normal}"
rm -rf "vendor"
echo "${bold}3/12 - Remove composer.lock${normal}"
rm composer.lock
echo "${bold}4/12 - Remove package-lock.json${normal}"
rm package-lock.json
echo "${bold}5/12 - composer install${normal}"
composer install
echo "${bold}6/12 - composer update${normal}"
composer update
echo "${bold}7/12 - php artisan migrate:reset${normal}"
php artisan migrate:reset
echo "${bold}8/12 - php artisan migrate${normal}"
php artisan migrate
echo "${bold}Do you want to seed test data?${normal}"
read seed
if [ $seed = 'y' ]; then
  echo "${bold}9/12 - php artisan db:seed${normal}"
  php artisan db:seed
fi
echo "${bold}10/12 - npm install${normal}"
npm install
echo "${bold}11/12 - npm update${normal}"
npm update
echo "${bold}12/12 - npm run prod${normal}"
npm run prod
echo "${bold}Finished... Check if everything went well...${normal}"