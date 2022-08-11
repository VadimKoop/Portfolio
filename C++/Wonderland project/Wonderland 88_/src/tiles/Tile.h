#ifndef _Tile_h_
#define _Tile_h_

#include <string>
#include <memory>
#include <ostream>
#include <queue>


class Player;

class Card;

//-----------------------------------------------------------------
class Tile
{
    friend class Board;

    friend class Player;

public:
    explicit Tile(const std::string &tileName);

    // specific to player
    virtual void enter(Player *player);

    virtual bool canEnter(Player *player) { return true; }

    void leave(Player *player);

    // specific to cards
    void addCard(std::shared_ptr<Card> &card);

    Card *getFirstCard();

    std::queue<Card*>& getCards(){ return cardsOnTile; }

    void reset();

protected:
    std::string name;
    std::shared_ptr<Tile> previousTile;
    std::shared_ptr<Tile> nextTile;

    std::queue<Card *> cardsOnTile;
};

class ExitWonderlandTile : public Tile
{
public:
    explicit ExitWonderlandTile(const std::string &tileName) : Tile(tileName)
    {};

    void enter(Player *player) override;

    bool canEnter(Player *player) override;
};

#endif // !_Tile_h_

