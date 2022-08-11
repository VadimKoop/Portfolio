cmake_minimum_required(VERSION 3.9)
project(Wonderland)

set(CMAKE_CXX_STANDARD 17)

add_executable(Wonderland src/main.cpp src/board/Board.cpp src/cards/Card.cpp src/player/Player.cpp src/tiles/Tile.cpp)

set(BUILD_SHARED_LIBS OFF)
set(CMAKE_EXE_LINKER_FLAGS ${CMAKE_EXE_LINKER_FLAGS} "-static")