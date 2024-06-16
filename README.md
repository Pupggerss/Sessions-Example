# Sessions-Example

A Pocketmine-MP plugin that implements the concept of sessions. (There are comments inside of the code. I recommend that
you know PHP OOP before you try to understand this: https://www.w3schools.com/php/php_oop_what_is.asp)

## Why use this?

To limit stress on your server! Constantly running database queries on your servers main thread can cause lag and alot
of performance issues.

## Can I do this with await-generator?

Yes! Await-Generator would be dealing with the database side while this is simply arrays.

# How to use?

First of all you need a session class, this class we'll refer to as player session. In the sessions class youll have
your constructor followed by methods. The constructor method is used to create a new instance, a player session and the
methods are used to get properties from the instance since you cannot access the private properties outside the class.

```php
class Session{ //Session class (player session)


    public function __construct( //Session constructor to create a new session instance
        private string $xuid,
        private string $username,
        private int $money //Properties are private to allow usage only in the Session class.
    ){
    }

    public function getXuid() : string{
        return $this->xuid;
    } //Method to get the players xuid (Xbox unique identifier) from the instance
    
}
```

You then need to make your database using whatever library or way you want, I
used [libasynql](https://github.com/poggit/libasynql). Do all the requirements then move on to the session manager.

The Session Manager is a private array that holds all player sessions.

```injectablephp
private array $sessions = []; //All player sessions will be added to this array 
```

In the Session Manager we create, close and get player sessions.

We first create a session by adding a methd, createSession which accepts Player as a parameter, this is done so you
could easily get the neccessary valuse needs from the player, like their xuid. Before the session is created we get
information from the database that would be needed in the session. Like money, then we create the session!

```php
 public function createSession(Player $player): void //Using the player class as a parameter then assigning it to the $player variable
    {
        $xuid = $player->getXuid(); //gets the player's xuid from the player
        $username = $player->getName();

        $db = Main::getInstance()->getDatabaseManager(); //assign the database manager to a variable

        $db->getMoney($xuid, function (array $moneyData) use ($xuid, $username, $db) {
            $playerMoney = $moneyData['money'] ?? 0; 
            //get the players money from the database and assigns 0 if there is none

            $session = new Session($xuid, $username, $playerMoney);
            //creates the players sessions instance
            $this->sessions[$xuid] = $session;
            /**  adds the players session to the sessions array 
             This array would look somewhat like this:
       [
            "Player1's XUID" => Player1's Session Class,
            "Player2's XUID" => Player2's Session Class,
            "Player3's XUID" => Player3's Session Class
       ]
       */
            $db->addPlayer($xuid, $username, $playerMoney);
            //adds the player to the database (Can be edited to be better like adding the player to the database only if they arent in it
        });
    }
  ```

We then close the session using the closeSession method which will get back the data from the session and put it in the
database then remove the session from the array.

The createSession method must be called on player join or login (preferably login) and close session must be called on
player quit.

##SAVE THE TO DATABASE IN INTERVALS OR EVERYTIME THE THE SESSION UPDATES USING AN EVENT
