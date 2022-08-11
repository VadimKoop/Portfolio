#include <gtest/gtest.h>
#include "MyLinkedList.h"

int mkdouble(int v) { return 2*v; }
int negate(int v) { return -v; }

TEST (List, Create) {
    srand ( time(NULL) );
    size_t n = static_cast <size_t> ( rand() % 99 + 1 );
    int v = rand() % 99 + 1;
    int delta = rand() % 20 + 5;

    MyLinkedList list1, list2(n,v);
    EXPECT_EQ (0, list1.getLength());

    size_t len = list2.getLength();
    for ( size_t i=0; i < len; i++ ) {
        EXPECT_EQ (v, list2[i]);
        list2[i] = v+delta;
        EXPECT_EQ (v+delta, list2[i]);
    }
}

TEST (List, CopyConstruct) {
    srand ( time(NULL) );
    int l = rand() % 99 + 1;
    int v = rand() % 99 + 1;
    MyLinkedList list(l,v), copy(list), copy2 = list;
    EXPECT_EQ(list.getLength(),copy.getLength());
    EXPECT_EQ(list.getLength(),copy2.getLength());
    for ( auto i=0; i < l; i++ ) {
        EXPECT_EQ(list[i],copy[i]);
        EXPECT_EQ(list[i],copy2[i]);
        copy[i] = copy[i]+i;
        copy2[i] = copy2[i]+2*i;
    }
    for ( auto i=0; i < l; i++ ) {
        EXPECT_EQ(list[i]+i,copy[i]);
        EXPECT_EQ(list[i]+2*i,copy2[i]);
    }
}

TEST (List, PushFront) {
    srand ( time(NULL) );
    int v = rand() % 99 + 1;
    MyLinkedList list1, list2(2,10);
    list1.push_front(v);
    EXPECT_EQ(10,list2[0]);
    EXPECT_EQ(10,list2[1]);
    list2.push_front(v);
    EXPECT_EQ(1,list1.getLength());
    EXPECT_EQ(v,list1[0]);
    EXPECT_EQ(3,list2.getLength());
    EXPECT_EQ(v,list2[0]);
    EXPECT_EQ(10,list2[1]);
    EXPECT_EQ(10,list2[2]);
}

TEST (List, PushBack) {
    srand ( time(NULL) );
    int v = rand() % 99 + 1;
    MyLinkedList list1, list2(2,10);
    list1.push_back(v);
    list2.push_back(v);
    EXPECT_EQ(1,list1.getLength());
    EXPECT_EQ(v,list1[0]);
    EXPECT_EQ(3,list2.getLength());
    EXPECT_EQ(10,list2[0]);
    EXPECT_EQ(10,list2[1]);
    EXPECT_EQ(v,list2[2]);
}

TEST (List, Remove) {
    MyLinkedList list(5,0);
    for ( auto i=0; i < 5; i++ ) {  list[i] = i+1;  }
    list.remove(4); for ( auto i=0; i < list.getLength(); i++ ) { EXPECT_EQ(i+1,list[i]); }
    list.remove(0); for ( auto i=0; i < list.getLength(); i++ ) { EXPECT_EQ(i+2,list[i]); }
    list.remove(1); for ( auto i=0; i < list.getLength(); i++ ) { EXPECT_EQ(2*(i+1),list[i]); }
}

TEST (List, Insert) {
    srand ( time(NULL) );
    int v = rand() % 99 + 1;
    MyLinkedList list;

    list.insert(0,v);
    EXPECT_EQ(1,list.getLength());
    EXPECT_EQ(v,list[0]);

    list.insert(0,2*v);
    EXPECT_EQ(2,list.getLength());
    EXPECT_EQ(2*v,list[0]);
    EXPECT_EQ(v,list[1]);

    list.insert(1,3*v);
    EXPECT_EQ(3,list.getLength());
    EXPECT_EQ(2*v,list[0]);
    EXPECT_EQ(3*v,list[1]);
    EXPECT_EQ(v,list[2]);

    list.insert(2,4*v);
    EXPECT_EQ(4,list.getLength());
    EXPECT_EQ(2*v,list[0]);
    EXPECT_EQ(3*v,list[1]);
    EXPECT_EQ(4*v,list[2]);
    EXPECT_EQ(v,list[3]);
}

TEST (List, ApplyUnaryOperator) {
    srand ( time(NULL) );
    int l = rand() % 99 + 1;
    int v = rand() % 99 + 1;
    MyLinkedList list(l,v), copy1(list), copy2(list);
    MyLinkedList::applyUnaryOperator(copy1,mkdouble);
    MyLinkedList::applyUnaryOperator(copy2,negate);

    for ( auto i = 0; i < l; i++ ) {
        EXPECT_EQ(2,copy1[i]/list[i]);
    }


    for ( auto i = 0; i < l; i++ ) {
        EXPECT_EQ(0,list[i]+copy2[i]);
    }

}

TEST (List, Equality) {
    srand ( time(NULL) );
    int l = rand() % 99 + 1;
    int v = rand() % 99 + 1;
    MyLinkedList list(l,v), list2(l,v), list3(list), list4(l+1,v), list5(l,v+1);
    EXPECT_TRUE(list == list2);
    EXPECT_TRUE(list2 == list3);
    EXPECT_TRUE(list == list3);
    EXPECT_FALSE(list == list4);
    EXPECT_FALSE(list2 == list4);
    EXPECT_FALSE(list2 == list4);
    EXPECT_FALSE(list == list5);
    EXPECT_FALSE(list2 == list5);
    EXPECT_FALSE(list3 == list5);
}














