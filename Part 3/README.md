## Team Project Part 3

### To Run:

1.
```
git clone "https://github.com/TegidGoodman-Jones/team-project-part3.git"
```

2. Install npm packages:
```
npm i
```

3. Now you need to add a sql database, here is a tutorial on how to set-up a local database (installing docker on Windows caused Ellie's pc to crash so it might be a good idea to find a different way of hosting a sql database on Windows) ) :

https://www.appsdeveloperblog.com/how-to-start-mysql-in-docker-container/
or
create some other mysql database

4. Add Database url to .env file, replacing all capital words with respective values:

```
DATABASE_URL="mysql://USER:PASSWORD@HOST:PORT/DATABASE"
```

5. Run the following command to push the prisma schema to the database:
```
npx prisma migrate dev
```
6. Visit /signUp to create a user in the database

**And you're done!**
