Hi, Deepak.

Thank you for the opportunity for this test.

I was so excited while I did the test.

Looking forward to hearing good news from you.

Kind regards

Kenneth Park

# How to run

1. Clone the repo
2. Run 'composer install'
3. Run './vendor/bin/sail up -d' once composer install completed
4. Run './vendor/bin/sail artisan migrate' after the container is running
5. You can play at http://localhost/

# API Document

http://localhost/request-docs

| METHOD | Endpoint                      | Description                                                                             |
|--------|-------------------------------|-----------------------------------------------------------------------------------------|
| GET    | /api/martians                 | Get data of all martians                                                                |
| POST   | /api/martians                 | Create a martian                                                                        |
| GET    | /api/martians/{martian}       | Get data of the specified martian                                                       |
| PUT    | /api/martians/{martian}       | Update data of the specified martian                                                    |
| DELETE | /api/martians/{martian}       | Delete the specified martian                                                            |
| POST   | /api/martians/{martian}/trade | Trade specified supplies of the martians with specified supplies of the selected trader |
| GET    | /api/supplies                 | Get data of all supplies                                                                |
| POST   | /api/supplies                 | Create a supply                                                                         |
| GET    | /api/supplies/{supply}        | Get data of the specified supply                                                        |
| PUT    | /api/supplies/{supply}        | Update data of the specified supply                                                     |
| DELETE | /api/supplies/{supply}        | Delete the specified supply                                                             |

# Screenshots

Index
![image description](screenshots/index.png)

Add Martian
![image description](screenshots/add-martian.png)

Trade failure
![image description](screenshots/trade-failure.png)

Try trading 5 Oxygen and 4 Medication to 2 Water and 10 Food
![image description](screenshots/try-trade-5-oxygen-4-medication-to-2-water-10-food.png)

Result of the trade 5 Oxygen and 4 Medication to 2 Water and 10 Food
![image description](screenshots/result-trade-5-oxygen-4-medication-to-2-water-10-food.png)

Try not to allow Martian(Kenneth Park) to trade
![image description](screenshots/try-not-to-allow-kenneth-park-to-trade.png)

Result of trying not to allow Martian(Kenneth Park) to trade
![image description](screenshots/result-of-try-not-to-allow-kenneth-park-to-trade.png)


--------------------------------------------------------------------------------------------------

# Backend Developer Test

Be sure to read **all** of this document carefully, and follow the guidelines within.

## Problem Description

Mars Trading Platform. It is a new civilization and there are no shops but people need supplies to survive. People
produce their own supplies and trade what they have for what they don't.

You have been tasked to develop a system to make the trading experience better.

## Requirements

You will develop a ***REST API***, which will store information about the martians, the supplies they own and ability to
trade.

In order to accomplish this, the API must fulfill the following use cases:

- **Add martians to the database**

  A martian must have a *name*, *age*, *gender*.

  A martian also has an inventory of supplies of their own property (which you need to declare when adding them to the
  database).

- **Allow martians to trade**

  To ensure the quality of the platform we should allow only top quality supplies to be traded. We need to have a way to
  flag the martian who don't meet our quality guidelines.

  An flagged martian cannot trade with others, can't access/manipulate their inventory.

- **Trade items**:

  Martians can trade supplies among themselves.

  To do that, they must respect the price table below, where the value of an item is described in terms of points.

  Both sides of the trade should offer the same amount of points. For example, 1 Oxygen and 1 Medication (1 x 6 + 1 x 2)
  is worth 2 Water (4 x 2) or 8 Clothing items (1 x 8).

  The trades themselves need not to be stored, but the items must be transferred from one martian to the other.

| Item         | Points   |
|--------------|----------|
| 1 Oxygen     | 6 points |
| 1 Water      | 4 points |
| 1 Food       | 3 points |
| 1 Medication | 2 points |
| 1 Clothing   | 1 point  |

---------------------------------------

## Notes

1. Please use PHP (Laravel/Symfony)
2. No authentication is needed (people are busy caring about their lives so no one will try to hack a system while
   trying to survive);
3. We still care about proper programming and architecture techniques
4. Don't forget to make at least a minimal documentation of the API endpoints and how to use them;
5. Bonus points if you write some automated tests;
6. From the problem description above you can either do a very bare bones solution or add optional features that are not
   described. Use your time wisely; the absolute optimal solution might take too long to be effective in the apocalypse,
   so you must come up with the best possible solution that will hold up within the least ammount of time and still be
   able to showcase your skills in order to prove your worth.
7. Write concise and clear commit messages, splitting your changes in little pieces.

## Environment

You are free to use any setup you like. We prefer docker and you can use a setup similar
to [Boilerplate](https://github.com/nanoninja/docker-nginx-php-mysql) or feel free to use your own. Just provide
instructions on how to run your application.

## Q&A

> Where should I send back the result when I'm done?

Fork this repo and send us a pull request when you think you are done. We will note you about deadline directly.

> What if I have a question?

Just create a new issue in this repo and we will respond and get back to you quickly.
