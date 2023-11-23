#include <iostream>
#include "MyLinkedList.h"

MyLinkedList::MyLinkedList()
        :
        m_firstNode(nullptr),
        m_lastNode(nullptr),
        m_listSize(0)
{

}

MyLinkedList::MyLinkedList(unsigned numOfElem, int value)
        :
        m_firstNode(nullptr),
        m_lastNode(nullptr),
        m_listSize(0)
{
    for (size_t i = 0; i < numOfElem; ++i)
    {
        push_back(value);
    }
}

MyLinkedList::MyLinkedList(const MyLinkedList& other)
        :
        m_firstNode(nullptr),
        m_lastNode(nullptr),
        m_listSize(0)
{
    for (size_t i = 0; i < other.getLength(); ++i)
    {
        push_back(other[i]);
    }
}

MyLinkedList::~MyLinkedList()
{
    //time to do some clean up
    ListNode* tempNode = m_firstNode;

    while (tempNode != nullptr)
    {
        ListNode* nodeToDelete = tempNode;

        tempNode = tempNode->m_ptrNext;
        m_listSize--;
        delete nodeToDelete;
    }

    m_firstNode = m_lastNode = nullptr;
}

//==========================================================================================================

const size_t MyLinkedList::getLength() const
{
    return m_listSize;
}

//==========================================================================================================

void MyLinkedList::push_front(int value)
{
    // checking all 3 conditions is an overhead(would be fine to check head node),
    // but taking into account the size of the program... why not?

    ListNode* newNode = new ListNode(value);

    // so it is our first element/ create and assign corresponding pointers ("head" and "tail")
    if (m_firstNode == nullptr && m_lastNode == nullptr && getLength() == 0)
    {
        m_firstNode = m_lastNode = newNode;
    }
    else
    {
        // 1. assing the pointer of the newly inserted node to point to the previous first one
        // 2. assing "head" node to point to the newly inserted node
        newNode->m_ptrNext = m_firstNode;
        m_firstNode = newNode;
    }

    m_listSize++;
}

//==========================================================================================================

void MyLinkedList::push_back(int value)
{
    // checking all 3 conditions is an overhead(would be fine to check head node),
    // but taking into account the size of the program... why not?

    ListNode* newNode = new ListNode(value);

    // so it is our first element/ create and assign corresponding pointers ("head" and "tail")
    if (m_firstNode == nullptr && m_lastNode == nullptr && getLength() == 0)
    {
        m_firstNode = m_lastNode = newNode;
    }
    else
    {
        // 1. assing the pointer of the last node in the list to point to the newly inserted
        // 2. assing tail node to point to the newly inserted node
        m_lastNode->m_ptrNext = newNode;
        m_lastNode = newNode;
    }
    m_listSize++;
}

//==========================================================================================================

void MyLinkedList::insert(size_t index, int value)
{
    // fool proof check
    // index 0? lets push_front
    if (index == 0)
    {
        push_front(value);
        return;
    }

    // last index? lets push_back
    if (index == m_listSize)
    {
        push_back(value);
        return;
    }

    // somewhere in the middle
    unsigned tempIndex = 0;
    ListNode* tempNode = m_firstNode;

    while (tempNode != nullptr)
    {
        if (tempIndex == index)
        {
            ListNode* newNode = new ListNode(value);
            ListNode* previousNode = getNodeAtIndex(index - 1);
            //next node that is currenly "sit" on our index
            ListNode* nextNode = getNodeAtIndex(index);

            previousNode->m_ptrNext = newNode;
            newNode->m_ptrNext = nextNode;

            m_listSize++;
            return;
        }
        tempIndex++;

        tempNode = tempNode->m_ptrNext;
    }
}

//==========================================================================================================

void MyLinkedList::remove(size_t index)
{

    ListNode* toRemoveNode = nullptr;

    // fool proof check
    // index 0? lets remove the first one
    if (index == 0)
    {
        ListNode* nextNode = getNodeAtIndex(index + 1);
        toRemoveNode = m_firstNode;
        m_firstNode = nextNode;
        m_listSize--;
        delete toRemoveNode;
        return;
    }

    // last index? lets remove the last one
    if (index == m_listSize - 1)
    {
        ListNode* previousNode = getNodeAtIndex(index - 1);
        toRemoveNode = m_lastNode;
        m_lastNode = previousNode;
        previousNode->m_ptrNext = nullptr;
        m_listSize--;
        delete toRemoveNode;
        return;
    }

    // somewhere in the middle
    unsigned tempIndex = 0;
    ListNode* tempNode = m_firstNode;

    while (tempNode != nullptr)
    {
        if (tempIndex == index)
        {
            ListNode* previousNode = getNodeAtIndex(index - 1);
            ListNode* nextNode = getNodeAtIndex(index + 1);

            toRemoveNode = tempNode;

            previousNode->m_ptrNext = nextNode;

            delete toRemoveNode;
            m_listSize--;
            return;
        }

        tempIndex++;
        tempNode = tempNode->m_ptrNext;
    }

    if (getLength() == 0)
    {
        m_firstNode = m_lastNode = nullptr;
    }
}

//==========================================================================================================

void MyLinkedList::applyUnaryOperator(MyLinkedList & list, int(*func)(int))
{
    ListNode* tempNode = list.m_firstNode;
    while (tempNode != nullptr)
    {
        tempNode->m_value = (*func)(tempNode->m_value);
        tempNode = tempNode->m_ptrNext;
    }
}

//==========================================================================================================

bool MyLinkedList::operator==(const MyLinkedList& other) const
{
    // no point in checking each value separately, if lists lengths do not match
    if (getLength() != other.getLength())
    {
        return false;
    }

    ListNode* tempNode = m_firstNode;
    ListNode* tempOtherNode = other.m_firstNode;

    while (tempNode != nullptr && tempOtherNode != nullptr)
    {
        if (tempNode->m_value != tempOtherNode->m_value)
        {
            return false;
        }

        tempOtherNode = tempOtherNode->m_ptrNext;
        tempNode = tempNode->m_ptrNext;
    }

    // woohoo, we are here, lists values match
    return true;
}

//==========================================================================================================

const int& MyLinkedList::operator[](const size_t index) const
{
    return const_cast<int&>(getValueAtIndex(index));
}

//==========================================================================================================

int& MyLinkedList::operator[](const size_t index)
{
    return getValueAtIndex(index);
}

MyLinkedList& MyLinkedList::operator=(const MyLinkedList & other)
{
    // IS THIS PART REALLY GOING TO BE CALLED???
    // are we trying to make a copy of ourselves?
    if(&other == this)
    {
        return *this;
    }
    // Ok, I honestly think we should really use some STL stuff here, but...
    for (size_t i = 0; i < other.getLength(); ++i)
    {
        push_back(other[i]);
    }

    return *this;
}

//==========================================================================================================

int& MyLinkedList::getValueAtIndex(size_t index) const
{
    unsigned tempIndex = 0;

    ListNode* tempNode = m_firstNode;

    while (tempNode != nullptr)
    {
        if (tempIndex == index)
        {
            return tempNode->m_value;
        }
        tempIndex++;

        tempNode = tempNode->m_ptrNext;
    }
}

//==========================================================================================================

MyLinkedList::ListNode * MyLinkedList::getNodeAtIndex(size_t index)
{
    unsigned tempIndex = 0;

    ListNode* tempNode = m_firstNode;

    while (tempNode != nullptr)
    {
        if (tempIndex == index)
        {
            return tempNode;
        }
        tempIndex++;

        tempNode = tempNode->m_ptrNext;
    }

    return nullptr;
}

//==========================================================================================================

std::ostream& MyLinkedList::getAllValuesToStream(std::ostream& stream) const
{
    // just cycle through all values and put those into stream for printing
    ListNode* node = m_firstNode;

    while (node != nullptr)
    {
        stream << node->m_value << " ";

        node = node->m_ptrNext;
    }

    stream << std::endl;

    return stream;
}

//==========================================================================================================

std::ostream& operator<<(std::ostream& stream, const MyLinkedList& list)
{
    return list.getAllValuesToStream(stream);
}