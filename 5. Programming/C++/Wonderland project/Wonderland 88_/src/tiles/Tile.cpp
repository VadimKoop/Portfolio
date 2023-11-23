#include <iostream>
#include <random>
#include <sstream>
#include <functional>
#include "Tile.h"
#include "../player/Player.h"

//-----------------------------------------------------------------
Tile::Tile(const std::string &tileName)
{
    name = tileName;
}

void Tile::enter(Player *player)
{
    std::cout << player->getName() << " entered " << this->name << std::endl;
}

void Tile::leave(Player* player)
{
    std::cout << player->getName() << " leaves " << this->name << std::endl;
}

void Tile::addCard(std::shared_ptr<Card>& card)
{
    cardsOnTile.push(std::move(card).get());
}

Card *Tile::getFirstCard()
{
    Card *returnCard;

    if (!cardsOnTile.empty())
    {
        returnCard = cardsOnTile.front();
        cardsOnTile.pop();
        return returnCard;
    }

    throw std::runtime_error("ERROR: There are no cards on the tile");
}

void Tile::reset()
{
    while (cardsOnTile.empty() == false)
    {
        cardsOnTile.pop();
    }
}

//-----------------------------------------------------------------
void ExitWonderlandTile::enter(Player *player)
{
    std::cout << player->getName() << " entered " << this->name << std::endl;

    if (canEnter(player) == false)
    {
        std::stringstream ss;

        std::string gender = player->getName().compare("Alice") == 0 ? "she" : "he";

        ss << "ERROR: " << player->getName() << " can not leave Wonderland as " << gender
           << " does not have the required cards";
        throw std::runtime_error(ss.str());
    }

    std::cout << player->getName() << " leaves wonderland and has collected: " << player->getCardsByName("prize").size()
              << " prize cards" << std::endl;
}

//modi
bool ExitWonderlandTile::canEnter(Player* player)
{
    if(player->getCardByName("Dispel") != nullptr)
    {
        return true;
    }
    return false;
}
