#Test no.2: Buat fungsi untuk menggambar array 2 dimensi
def isiArray(height, width):
    for i in range(height):
        EMPTY = i +2
        row = [(EMPTY * 2 * (i+1)) for i in range(width)]
        print(row)

isiArray(3,5)