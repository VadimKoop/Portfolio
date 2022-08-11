cmake_minimum_required(VERSION 3.9)
project(HW01)

set(CMAKE_CXX_STANDARD 17)

set(CMAKE_VERBOSE_MAKEFILE ON)
set(CMAKE_COLOR_MAKEFILE   ON)

set(THREADS_PREFER_PTHREAD_FLAG ON)
find_package(Threads REQUIRED)

add_executable(HW01 src/hw01.cpp src/MyLinkedList.cpp src/MyLinkedList.h)

include_directories(src/gtest/include src/gtest)
add_executable(RunTests src/gtest/src/gtest_main.cc src/gtest/src/gtest-all.cc src/hw01test.cpp src/MyLinkedList.cpp src/MyLinkedList.h)
target_link_libraries(RunTests Threads::Threads)


