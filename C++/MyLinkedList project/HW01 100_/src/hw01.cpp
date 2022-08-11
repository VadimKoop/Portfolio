#include <iostream>
#include "MyLinkedList.h"

int mkdouble(int v) { return 2*v; }
int negate(int v) { return -v; }

int main() {

  std::cout << "Let's see your progress" << std::endl << std::endl;

  MyLinkedList list, list2(5,10), list3(list), list4(list2), list5=list, list6=list2;
    // MyLinkedList list, list2(5, 10), list3(list), list4(list2), list5 = list;
    // MyLinkedList list6 = list2;
  MyLinkedList list7(list2), list8, list9, list10, list11(3,5);
    // For testing only! This will call copy-assigment, but what about those above?
    //list7 = list11;

  std::cout << "Can construct a list" << std::endl;
  std::cout << "\tAn empty list has " << list.getLength() << " elements." << std::endl;
  std::cout << "\tA non-empty list has " << list2.getLength() << " elements, and its contents are " << list2 << std::endl;
  std::cout << std::endl;
  std::cout << "Can copy-construct a list" << std::endl;
  std::cout << "\tAn empty list has " << list3.getLength() << " elements, and its contents are " << list3 << std::endl;
  std::cout << "\tA non-empty list has " << list4.getLength() << " elements, and its contents are " << list4 << std::endl;
  std::cout << std::endl;
  std::cout << "Can copy-assign a list" << std::endl;
  std::cout << "\tAn empty list has " << list5.getLength() << " elements, and its contents are " << list5 << std::endl;
  std::cout << "\tA non-empty empty list has " << list6.getLength() << " elements, and its contents are " << list6 << std::endl;
  std::cout << std::endl << "Can print a list" << std::endl << "\tObviously (as you can see)" << std::endl;
  std::cout << std::endl;
  std::cout << "Operator [] works" << std::endl;
  std::cout << "\tlist7[3] = " << list7[3] << std::endl;
  std::cout << "\tSetting list7[3] to 100..." << std::endl; list7[3] = 100;
  std::cout << "\tlist7[3] = " << list7[3] << std::endl;
  std::cout << std::endl;
  std::cout << "push_front() works" << std::endl;
  for(auto i=0; i<10; i++) {
    list8.push_front(i);
    std::cout << "\tPushing " << i << " to the beginning of the list " << list8 << std::endl;
  }
  std::cout << std::endl;
  std::cout << "push_back() works" << std::endl;
  for(auto i=0; i<10; i++) {
    list8.push_back(i);
    std::cout << "\tPushing " << i << " to the end of the list " << list8 << std::endl;
  }
  std::cout << std::endl;
  std::cout << "push_front() and push_back() can work together" << std::endl;
  for(auto i=0; i<10; i++) {
    list9.push_front(i);
    list9.push_back(i);
    std::cout << "\tPushing " << i << " to the beginning and end of the list " << list9 << std::endl;
  }
  std::cout << std::endl;
  std::cout << "Insert works" << std::endl;
  for(auto i=0; i<10; i++) {
    list10.insert(0,i);
    std::cout << "\tInserting " << i << " into position 0 of the list thus getting " << list10 << std::endl;
  }
  list10.insert(2,15); std::cout << "\tInserting 15 into position 2 of the list thus getting " << list10 << std::endl;
  list10.insert(10,50); std::cout << "\tInserting 50 into position 10 of the list thus getting " << list10 << std::endl;
  list10.insert(12,125); std::cout << "\tInserting 125 into position 12 of the list thus getting " << list10 << std::endl;
  for(auto i : {1,3,5}) {
    list11.insert(i,2);
    std::cout << "\tInserting 2 into position " << i << " of the list thus getting " << list11 << std::endl;
  }
  std::cout << std::endl;
  std::cout << "remove() works" << std::endl;
  for(auto i=0; i<2; i++) {
    list8.remove(0);
    std::cout << "\tRemoving element at position 0 from list thus getting " << list8 << std::endl;
  }
  for(auto i=0; i<3; i++) {
    size_t len = list8.getLength()-1;
    list8.remove(len);
    std::cout << "\tRemoving element at position " << len << " from list thus getting " << list8 << std::endl;
  }
  for(size_t i=0; i<list8.getLength(); i++) {
    if ( i%2 == 1 ) {
      list8.remove(i);
    }
  }
  std::cout << "\tRemoving elements at even positions in list 8 getting " << list8 << std::endl;
  while ( list8.getLength() ) {
    list8.remove(0);
    std::cout << "\tRemoving element at position 0 of list8 until it is empty " << list8 << std::endl;
  }
  std::cout << std::endl;
  std::cout << "Equality operator works" << std::endl;
  MyLinkedList list12(list11);
  std::cout << "\tList " << list12 << " is equal to list " << list11 << ": " << std::boolalpha << (list12 == list11) << std::endl;
  std::cout << "\tList " << list12 << " is equal to list " << list4 << ": " << std::boolalpha << (list12 == list4) << std::endl;
  std::cout << std::endl;
  std::cout << "ApplyUnaryOperator works" << std::endl;
  std::cout << "\tList 11 now contains the following items " << list11 << std::endl;
  MyLinkedList list13(list11),list14(list11);
  MyLinkedList::applyUnaryOperator(list13,mkdouble);
  MyLinkedList::applyUnaryOperator(list14,negate);
  std::cout << "\tApplying mkdouble() we get " << list13 << std::endl;
  std::cout << "\tApplying negate() we get " << list14 << std::endl;

  return 0;
}
