This work is more of a concept than a program.
The idea behind this is to use an old Java pattern for database access.
- The Data Access Object (DAO) does the database work
- The Data Transfer Object (DTO) is the "bean" 

The DAO knows how to talk to the database.
The BaseDAO is set up to the MySQL and memcached.
It is simple enough to change the BaseDAO to use a different DB or a different caching tool.
