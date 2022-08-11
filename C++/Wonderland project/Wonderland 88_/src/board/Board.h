#ifndef _Board_h_
#define _Board_h_

class Tile;
class Card;

class Board 
{
public:
  Board() = default;
  std::shared_ptr<Tile> getStartTile();
  Board& append(std::shared_ptr<Tile> tile);

private:
  std::shared_ptr<Tile> startTile;
  std::shared_ptr<Tile> lastTile;
};
#endif //_Board_h_

