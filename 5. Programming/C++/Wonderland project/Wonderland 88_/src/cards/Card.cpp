#include "iostream"
#include "Card.h"
#include "../player/Player.h"

//-----------------------------------------------------------------
void StickyCard::onDropped(Player *player)
{
    throw std::runtime_error("ERROR: Sticky card cannot be dropped!");
}

bool StickyCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Sticky card. Bad luck!" << std::endl;

    return true;
}

//-----------------------------------------------------------------
void VitalityCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Vitality card" << std::endl;

    player->setEnchancedVitality(false);
}

bool VitalityCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Vitality card" << std::endl;

    player->setEnchancedVitality(true);

    return true;
}

//-----------------------------------------------------------------
void PrizeCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Prize card" << std::endl;
}

bool PrizeCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up a Prize card" << std::endl;

    return true;
}

//-----------------------------------------------------------------
void DispelCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Dispel card and cannot leave Wonderland anymore" << std::endl;
}

bool DispelCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Dispel card and can leave Wonderland now" << std::endl;

    return true;
}

//-----------------------------------------------------------------
void RestlessExplorerCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Restless Explorer card and now can get tired again" << std::endl;
}

bool RestlessExplorerCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Restless Explorer card and now is never tired" << std::endl;

    return true;
}

//-----------------------------------------------------------------
void DistractCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Distract card and now can get tired again" << std::endl;
}

bool DistractCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Distract card and will now loose one random card" << std::endl;
    player->dropRandomCard();

    return false;
}

//-----------------------------------------------------------------
void RewindCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Rewind card" << std::endl;
}

bool RewindCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Rewind card and will now start from the beginning" << std::endl;
    player->reset();

    return false;
}

//-----------------------------------------------------------------
void ZippyCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Zippy card" << std::endl;
}

bool ZippyCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Zippy card, vitality increases by 1 point" << std::endl;

    player->changeVitalityByValue(1);

    return false;
}

//-----------------------------------------------------------------
void HypnoticCard::onDropped(Player *player)
{
    std::cout << player->getName() << " has dropped Hypnotic card" << std::endl;
}

bool HypnoticCard::onPicked(Player *player)
{
    std::cout << player->getName() << " has picked up Hypnotic card and looses all vitality" << std::endl;

    // use the same method
    player->setVitality(0);

    return false;
}
