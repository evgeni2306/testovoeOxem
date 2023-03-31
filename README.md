Консольная команда - jsonOperation
Для ее работы нужно поместить categories.json и products.json в storage/app/

Краткая документация по API:

АВТОРИЗАЦИЯ
POST: /api/registration - принимает[name,surname,email,password], возвращает аутентификационный ключ authKey
POST: /api/login - принимает[email,password], возвращает аутентификационный ключ authKey

КАТЕГОРИИ
POST: /api/category/create - принимает[name,authKey,external_id,parent_id], возвращает id созданной категории
POST: /api/category/update - принимает[name,authKey,external_id,parent_id], возвращает сообщение об успехе операции
POST: /api/category/delete - принимает[authKey,external_id], возвращает сообщение об успехе операции
GET: /api/category/list - принимает[], возвращает массив объектов в JSON

ТОВАРЫ
POST: /api/product/create - принимает[name,description,authKey,external_id,price,quantity, category_id], возвращает id созданного товара
POST: /api/product/update - принимает[name,description,authKey,external_id,price,quantity, category_id], возвращает сообщение об успехе операции
POST: /api/product/delete - принимает[authKey,external_id], возвращает сообщение об успехе операции
POST: /api/product/concrete - принимает[external_id], возвращает объект в JSON
POST: /api/product/category - принимает[external_id], возвращает массив объектов в JSON
GET: /api/product/category - принимает[page,sort,field], возвращает объект пагинации
