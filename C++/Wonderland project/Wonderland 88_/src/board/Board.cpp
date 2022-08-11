#include <memory>
#include "Board.h"
#include "../tiles/Tile.h"


std::shared_ptr<Tile> Board::getStartTile() 
{
  if (startTile == nullptr) 
  {
    throw std::runtime_error("ERROR: A board has no start tile");
  }
  return startTile;
}

Board& Board::append(std::shared_ptr<Tile> tile)
{
  if (startTile == nullptr)
  {
    lastTile = startTile = std::move(tile);
  }
  else 
  {
    lastTile->nextTile = std::move(tile);
    lastTile->nextTile->previousTile = lastTile;
    lastTile = lastTile->nextTile;
  }
  return *this;
}
