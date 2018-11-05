# MyAlpineRocks
Learning project - web shop backend

Assignment:
Make an application for managing categories and products. One product can belong to more than one category, and one category can have more than one product. You should save the category ID, name and description in a database. The categories should be organized hierarchically. Every category can have an unlimited number of subcategories, and at the same time every category can have at most one parent-category (parent-child relationship). You should save the product ID, name, description, price and photos of the products (unlimited number of photos).
Create a database (MySQL), backend and a service part of application. Implementation of the application should be done using OOP and (optionally) MVC pattern.
No framework should be used. 
Backend:
Only registered users have access to the backend. When the user is logged in, their name and the profile photo are located in in the corner of the screen.  There are 3 types of users: readers, writers and administrators. The reader can only read the data, the writer can insert and update data of categories and products, and the administrator can create, update and delete data about products, categories and users. The following data about the user should be kept in the database: ID, name and surname, emai, photo, password and auto-generated API key.
Make the screens for the category, product and user administration (adding, editing and deleting). 

An application should also provide REST services. The responses should be sent in JSON or XML format. You should have two services, one for managing categories (url: domainname/api/category/*) and another for managing products (url: domainname/api/product/*). An user authentication should be done through request header which should contain an API key. The user rights should be identical to those in the backend. Services should provide CRUD functionalities. Use GET request for reading, POST for creating, PATCH for editing and DELETE request for deleting the data.
