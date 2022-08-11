#include <sstream>
#include <thread>
#include <random>
#include <iostream>
#include <algorithm>
#include "Player.h"
#include "../tiles/Tile.h"
#include "../cards/Card.h"

//-----------------------------------------------------------------
Player::Player(const std::string &playerName, std::shared_ptr<Tile> &&tile)
        :
        name(playerName),
        vitality(kMaxVitality),
        cardsCount(0)
{
    currentPosition = startPosition = std::move(tile);
    currentPosition->enter(this);
}

//-----------------------------------------------------------------
void Player::forward(bool ordinaryPlayer)
{
    if (currentPosition->nextTile == nullptr)
    {
        std::stringstream ss;
        ss << "ERROR: " << currentPosition->name << " is the last tile in Wonderland sequence and " << name
           << " cannot move forward.";
        throw std::runtime_error(ss.str());
    }

    if (vitality == 0 && ordinaryPlayer && getCardByName("Restless") == nullptr)
    {
        std::stringstream ss;
        ss << "ERROR: " << name << " is too tired to move forward";
        throw std::runtime_error(ss.str());
    }

    currentPosition->leave(this);
    currentPosition = currentPosition->nextTile;
    currentPosition->enter(this);

    if(ordinaryPlayer && getCardByName("Restless") == nullptr)
    {
        vitality--;
    }
}

//-----------------------------------------------------------------
void Player::backward(bool ordinaryPlayer)
{
    if (!currentPosition->previousTile)
    {
        std::stringstream ss;
        ss << "ERROR: " << currentPosition->name << " is the first tile in Wonderland and " << name
           << " cannot move backward";
        throw std::runtime_error(ss.str());
    }

    if (vitality == 0 && ordinaryPlayer)
    {
        std::stringstream ss;
        ss << "ERROR: " << name << " is too tired to move backwards";
        throw std::runtime_error(ss.str());
    }

    currentPosition->leave(this);
    currentPosition = currentPosition->previousTile;
    currentPosition->enter(this);

    if(ordinaryPlayer) vitality--;
}

//-----------------------------------------------------------------
void Player::reset()
{
    currentPosition->leave(this);
    currentPosition = startPosition;
    currentPosition->enter(this);
}

//-----------------------------------------------------------------
int Player::getVitality() const
{
    std::cout << name << " has " << vitality << " vitality points" << std::endl;

    return vitality;
}

//-----------------------------------------------------------------
int Player::getMaxVitality() const
{

    if(getCardByName("Vitality") != nullptr)
    {
        std::cout << name << " has " << kMaxEnchancedVitality << " maximum vitality points" << std::endl;
        return kMaxEnchancedVitality;
    }
    else
    {
        std::cout << name << " has " << kMaxVitality << " maximum vitality points" << std::endl;
        return kMaxVitality;
    }
}

//-----------------------------------------------------------------
void Player::pickUp()
{
    if (cardsCount == kMaxCardsCount)
    {
        std::stringstream ss;
        ss << "ERROR: " << name << " can not pick another Card as maximum amount of cards has been reached";
        throw std::runtime_error(ss.str());
    }
    Card *card = currentPosition->getFirstCard();
    if (card != nullptr)
    {
        if(card->onPicked(this))
        {
            playerCards.push_back(card);
            cardsCount++;
        }
    }
    else
    {
        std::stringstream ss;
        ss << "ERROR: " << name << " can not pick a card at this location and current tile has none";
        throw std::runtime_error(ss.str());
    }
}

//-----------------------------------------------------------------
void Player::drop(Card* card)
{
    Card *requiredCard = getCardByName(card->getCardName());

    if (requiredCard != nullptr)
    {
        std::vector<Card*>::iterator it = std::find(playerCards.begin(), playerCards.end(), requiredCard);
        card->onDropped(this);
        currentPosition->cardsOnTile.push(card);
        playerCards.erase(it);
    }
    else
    {
        std::stringstream ss;
        ss << "ERROR: " << card->getCardName() << " card can not be dropped as " << name << " does not have it" << std::endl;
        throw std::runtime_error(ss.str());
    }
}

//-----------------------------------------------------------------
std::vector<Card*> &Player::getCards()
{
    std::cout << name << " has " << playerCards.size() << " cards" << std::endl;

    return playerCards;
}

//-----------------------------------------------------------------
Card *Player::getCardByName(std::string cardName) const
{
    for (auto elem : playerCards)
    {
        if (elem->getCardName() == cardName)
        {
            return elem;
        }
    }

    return nullptr;
}

//-----------------------------------------------------------------
std::vector<Card*> Player::getCardsByName(std::string cardName)
{

    std::vector<Card*> returnVector;

    for (auto elem : playerCards)
    {
        if (elem->getCardName() == cardName)
        {
            returnVector.push_back(elem);
        }
    }
    return returnVector;
}

//-----------------------------------------------------------------
void Player::sleep()  // true? or false:
{
    getCardByName("Vitality") != nullptr ? vitality = kMaxEnchancedVitality : vitality = kMaxVitality;
}

//-----------------------------------------------------------------
void Player::setEnchancedVitality(bool value)
{
    if (value)
    {
        vitality++;
        std::cout << name << "'s vitality has enchanced. Vitality gets +1 point, maximum is increased by 1 point"
                  << std::endl;
    }
    else
    {
        std::cout << name << "'s vitality is back to normal. Vitality maximum is decreased by 1 point"
                  << std::endl;
    }
}

//-----------------------------------------------------------------
void Player::dropRandomCard()
{
    std::default_random_engine rng(std::chrono::high_resolution_clock::now().time_since_epoch().count());
    std::uniform_int_distribution<int> distribution(0, playerCards.size() - 1);   //0,1,2,3,4 cards

    auto generateIndex = std::bind(distribution, rng);

    int index = generateIndex();

    // sticky card can not be erased
    if(getCardByName("Sticky") == nullptr)
    {
        playerCards.erase(playerCards.begin() + index);
    }
    else
    {
        while(playerCards[index]->getCardName() == "Sticky")
        {
            index = generateIndex();
        }
        playerCards.erase(playerCards.begin() + index);
    }
}

void Player::changeVitalityByValue(int value)
{
    vitality += value;
}

//-----------------------------------------------------------------
// FLYING PLAYER
//-----------------------------------------------------------------

FlyingPlayer::FlyingPlayer(const std::string &playerName, std::shared_ptr<Tile> &&tile)
        :
        Player(playerName, std::move(tile))
{
}

void FlyingPlayer::forward(bool ordinaryPlayer)
{
    // pass false to indicate that this is a flying player
    Player::forward(false);
}

//-----------------------------------------------------------------
void FlyingPlayer::backward(bool ordinaryPlayer)
{
    // pass false to indicate that this is a flying player
    Player::backward(false);
}

//-----------------------------------------------------------------
void FlyingPlayer::pickUp()
{
    if (cardsCount == kMaxCardsCount)
    {
        std::stringstream ss;
        ss << "ERROR: " << name << " cannot pick another Card as maximum has been reached";
        throw std::runtime_error(ss.str());
    }
    Card *card = currentPosition->getFirstCard();
    playerCards.push_back(card);
    card->onPicked(this);

    cardsCount++;
}
