# mse_likes_and_dislikes_plugin
### Инструкция по тестированию
1. `Управление / Плагины / Веб-службы` надо включить веб службы и поставить rest (первые 2 строчки)
2. `Личный кабинет/Администрирование/Плагины/Веб-службы/Управление ключами` - создать токен для некоторого пользователя
3. `Личный кабинет/Администрирование/Плагины/Веб-службы/Внешние службы` - выбрать moodle_mobile_service и включить его.  

`MOODLE_ROOT` - домашная страница moodle
`TOKEN` - токен пользователя, имеющего доступ к блоку
`COURSE` - id курса, на котором будет проводиться тестирование
`MODULE` - id активности в выбранном курсе, на котором будет проводиться тестирование
