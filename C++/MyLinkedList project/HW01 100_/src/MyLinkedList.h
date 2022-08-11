#ifndef _MY_LINKED_LIST_H_
#define _MY_LINKED_LIST_H_

class MyLinkedList
{

public:

    struct ListNode
    {
        ListNode(int value) : m_value(value), m_ptrNext(nullptr) {};
        int m_value;
        ListNode* m_ptrNext;
    };
    MyLinkedList();
    MyLinkedList(unsigned numOfElem, int value);
    MyLinkedList(const MyLinkedList& other);

    virtual ~MyLinkedList();

    // accessors
    const size_t getLength() const;
    std::ostream& getAllValuesToStream(std::ostream& stream) const;

    //modifiers
    void push_front(int value);
    void push_back(int value);
    void insert(size_t index, int value);
    void remove(size_t index);

    static void applyUnaryOperator(MyLinkedList& list, int(*func)(int));

    // operator overload
    bool operator==(const MyLinkedList& other) const;
    const int& operator[](const size_t index) const;
    int& operator[](const size_t index);
    MyLinkedList& operator=(const MyLinkedList& other);

private:
    // member methods
    int& getValueAtIndex(size_t index) const;
    ListNode* getNodeAtIndex(size_t index);

private:
    ListNode* m_firstNode;
    ListNode* m_lastNode;
    size_t m_listSize;
};

std::ostream& operator<<(std::ostream& stream, const MyLinkedList& list);

#endif // _MY_LINKED_LIST_H_

