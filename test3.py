#Test no.3: fungsi untuk mencetak segitiga terbalik
def cetakPola(pattern):
    # looping terbalik parameter yang diterima di tambahkan 1
    for i in reversed(range((pattern+1))):
        print('*' * i)

cetakPola(7)