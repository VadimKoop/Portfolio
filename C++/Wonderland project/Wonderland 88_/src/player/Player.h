#ifndef _Player_h_
#define _Player_h_

#include <string>
#include <memory>
#include <vector>

class Tile;
class Card;

//-----------------------------------------------------------------
class Player
{
public:

    Player(const std::string& playerName, std::shared_ptr<Tile> &&tile);

    virtual void forward(bool ordinaryPlayer = true);

    virtual void backward(bool ordinaryPlayer = true);

    virtual void reset();

    virtual void sleep();

    virtual void pickUp();

    virtual void drop(Card *card);

    virtual const std::string& getName() const { return name; }

    virtual Card* getCardByName(std::string cardName) const;

    virtual std::vector<Card*> getCardsByName(std::string cardName);

    virtual std::vector<Card*> &getCards();

    // getters
    virtual int getVitality() const;

    virtual int getMaxVitality() const;

    // card triggered modifiers
    virtual void setEnchancedVitality(bool value);

    virtual void dropRandomCard();

    virtual void changeVitalityByValue(int value);

    virtual void setVitality(int value) { vitality = value; }

protected:
    std::string name;
    std::shared_ptr<Tile> startPosition;
    std::shared_ptr<Tile> currentPosition;
    const unsigned kMaxVitality = 3;
    const unsigned kMaxEnchancedVitality = 4;
    unsigned vitality;
    int cardsCount;
    const int kMaxCardsCount = 5;
    std::vector<Card*> playerCards;
};

//-----------------------------------------------------------------
// FLYING PLAYER
//-----------------------------------------------------------------
class FlyingPlayer : public Player
{
public:
    FlyingPlayer(const std::string &playerName, std::shared_ptr<Tile> &&tile);

    void forward(bool ordinaryPlayer) override;

    void backward(bool ordinaryPlayer) override;

    void pickUp() override;

private:
    const int kMaxCardsCount = 3;
};

#endif // !_Player_h_
