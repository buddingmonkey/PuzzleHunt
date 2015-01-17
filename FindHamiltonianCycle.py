__author__ = 'aeiche'
#!/usr/bin/env python
# -*- coding: utf-8 -*-
# vim:ts=4:et:sw=4

class Vertex(object):
    def __init__(self, node, *nodeList):
        self.i = node
        self.nodeList = list(nodeList)

    def __hash__(self):
        return self.i

    def reaches(self, vertex):
        ''' Can receive an integer or a Vertex
        '''
        if isinstance(vertex, int):
            return vertex in self.nodeList

        return self.reaches(vertex.i)

    def __str__(self):
        return '< ' + str(self.i) + '>'

    def __repr__(self):
        return self.__str__()


class Graph(object):
    def __init__(self):
        self.vList = {}

    def add(self, node, *nodeList):
        vertex = Vertex(node, *nodeList)
        self.vList[node] = vertex

    def hamiltonian(self, current = None, pending = None, destiny = None):
        ''' Returns a list of nodes which represent
        a hamiltonian path, or None if not found
        '''
        if pending is None:
            pending = self.vList.values()

        result = None

        if current is None:
            for current in pending:
                result = self.hamiltonian(current, [x for x in pending if x is not current], current)
                if result is not None:
                    break
        else:
            if pending == []:
                if current.reaches(destiny):
                    return [current]
                else:
                    return None

            for x in [self.vList[v] for v in current.nodeList]:
                if x in pending:
                    result = self.hamiltonian(x, [y for y in pending if y is not x], destiny)
                    if result is not None:
                        result = [current] + result
                        break

        return result

if __name__ == '__main__':
    G = Graph()
    G.add(11, 22, 9)
    G.add(6, 14, 12, 9, 5, 1)
    G.add(1, 6, 8, 20, 22)
    G.add(24, 20, 3, 11)
    G.add(2, 1, 6)
    G.add(2, 18, 17)
    G.add(24, 1, 10, 12, 2)
    G.add(3, 14, 1, 6)
    G.add(3, 21, 10, 2, 18, 1)
    G.add(16, 18, 6, 9)
    G.add(24, 17, 21, 4)
    G.add(5, 23, 17, 19)
    G.add(20, 1, 22, 14)
    G.add(10, 15, 7, 8)
    G.add(1, 18, 19, 12)
    G.add(1, 6, 5, 10, 2)
    G.add(19, 1, 3)
    G.add(20, 1, 19, 6)
    G.add(21, 2, 15, 12, 6)
    G.add(1, 21, 15, 23)
    G.add(19, 20, 3, 14, 22)
    G.add(15, 21, 6, 2, 20, 13, 14)
    G.add(10, 19, 1, 12, 22, 6)
    G.add(1)
    print G.hamiltonian()