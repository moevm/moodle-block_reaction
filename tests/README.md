# mse_likes_and_dislikes_plugin
### Инструкция по тестированию
##### В Moodle нужно установить следующие параметры
1. `Управление / Плагины / Веб-службы` надо включить веб службы и поставить rest (первые 2 строчки)
2. `Личный кабинет/Администрирование/Плагины/Веб-службы/Управление ключами` - создать токен для некоторого пользователя
3. `Личный кабинет/Администрирование/Плагины/Веб-службы/Внешние службы` - выбрать moodle_mobile_service и включить его.  

##### Подготовка тестов
Из дерикетории с тестами
1. Установить внешние пакеты с помощью `npm i`
2. Запустить тестирование `npm test` 

`MOODLE_ROOT` - домашная страница moodle  
`TOKEN` - токен пользователя, имеющего доступ к блоку  
`COURSE` - id курса, на котором будет проводиться тестирование  
`MODULE` - id активности в выбранном курсе, на котором будет проводиться тестирование  

