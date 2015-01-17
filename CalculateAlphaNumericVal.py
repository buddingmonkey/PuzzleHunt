__author__ = 'aeiche'
import string
import sys
menu = ['sesame tofu',
        'vegetarian spring roll',
        'baby bokchoy',
        'peapod shoots',
        'peking ravioli',
        'orange chicken',
        'pork with green squash',
        'moo shi pork',
        'mongolian beef',
        'general gaus chicken',
        'beef with mushroom and peapods',
        'yu hsiang scallops',
        'moo shi chicken',
        'beef teriyaki',
        'shao xing pork',
        'szechuan spicy chicken',
        'vegetarian delight',
        'hot pot fish',
        'chinese eggplant',
        'fresh whole fish with sweet and sour sauce',
        'chicken velvet with shrimp soup',
        'baby shrimp with szechuan tomato sauce',
        'szechuan pork belly',
        'dry cooked sliced beef']

menu_sum = []

for i,m in enumerate(menu):
    menu_sum.append(0);
    for c in m:
        if c.isalpha():
            print ord(c) - 96
            menu_sum[i] += ord(c) - 96

for s in menu_sum:
    s = s % 26
    sys.stdout.write(chr(96 + s))