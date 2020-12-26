#Test no.1: Aplikasi sederhana untuk menghitung gaji bersih seorang karyawan
#funsi yang menerima 4 parameter
def hitungGaji(name, salary, counts, option):
    #menbuat nilai tunjangan bersarakan parameter option
    options = 500000 if option == True else 0
    print("====================================")
    print("Nama Karyawan: " + name)
    print("Gaji Pokok: Rp" + str(salary))
    #Print nilai tunjangan berdasarkan parameter option
    print("Tunjangan: Rp" + str(options)) if option == True else 0
    #Hitung BPJS, Pajak dan hasil akhir dan print ke terminal
    bpjs = (salary * 3) / 100
    print("BPJS: Rp" +str(bpjs) )
    pajak = (salary * 5) / 100
    print("Pajak: Rp" +str(pajak) )
    result = salary + options - bpjs - pajak
    print("====================================")
    print("Gaji Bersih: Rp"+ str(result) +"/Bulan")
    print("====================================")
    print("Total Gaji: Rp" + str(result * counts))
    print("====================================")

hitungGaji("andi", 1500000, 2, True)