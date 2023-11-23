#ifndef _Card_h_
#define _Card_h_

class Player;

//-----------------------------------------------------------------
class Card {
public:
    explicit Card(const std::string &cardName) : cardName(cardName) {}

    virtual void onDropped(Player *player) = 0;

    virtual bool onPicked(Player *player) = 0;

    virtual const std::string &getCardName() const { return cardName; }

private:
    std::string cardName;
};

//-----------------------------------------------------------------
class PrizeCard : public Card {
public:
    explicit PrizeCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class VitalityCard : public Card {
public:
    explicit VitalityCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class StickyCard : public Card {
public:
    explicit StickyCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class DispelCard : public Card {
public:
    explicit DispelCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class RestlessExplorerCard : public Card {
public:
    explicit RestlessExplorerCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class DistractCard : public Card {
public:
    explicit DistractCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class RewindCard : public Card {
public:
    explicit RewindCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class ZippyCard : public Card {
public:
    explicit ZippyCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

//-----------------------------------------------------------------
class HypnoticCard : public Card {
public:
    explicit HypnoticCard(const std::string &cardName) : Card(cardName) {};

    void onDropped(Player *player) override;

    bool onPicked(Player *player) override;
};

#endif // !_Card_h_

