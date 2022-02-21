#choose-your-own-adventure generator
#AP Computer Science Principles Create Task

import time

#choose-your-own-adventure template
content = ["You are at the entrance to a cave.",
    ["Enter the cave",
        ["It is dark.",
            ["Turn on your flashlight",
                ["The end",
                    "You see a monster and panic, causing you to slip and fall on your face."
                ]
            ],
            ["Leave the flashlight off",
                ["How unfortunate.",
                    "You ran into a monster and got gobbled up alive."
                ]
            ]
        ]
    ],
    ["Run away",
        ["Good choice.",
            "You survived."
        ]
    ],
]

def display(content):
    if len(content) == 2 and isinstance(content[1], str):
        #player has reached the end
        print(f"{content[0]}\n{content[1]}")
        time.sleep(3)
        exit()
    else:
        print(f"{content[0]}\n===")
        for i in content[1:]:
            print(f"[{content.index(i)}] {i[0]}")

def traverse(content, location):
    #content reduced to current path
    for i in location:
        content = content[i]

    #display prompt and options
    print(content)
    display(content)

    #input handling loop
    while 1:
        choice = input(": ")
        try:
            choice = int(choice)

            if choice < 1 or choice >= len(content):
                raise NameError("choice out of range")
            else:
                location.append(choice)
                location.append(1)
                print(location)
                print("")
                return location
        except Exception as e:
            print("Invalid choice")

def start(content):
    #location stored as list of indexes leading to current path
    location = []
    while 1:
        location = traverse(content, location)

start(content)
