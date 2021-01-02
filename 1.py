def hitungUangKembalian(payment, price):
  #Buat list kembalian
  money = [100000, 50000, 20000, 10000, 5000, 2000, 1000, 500, 100]
  #Buat Dictionary keterangan
  dicts = {}
  #Hitung hasil pembayaran
  change = payment - price
  #Loop item didalam list kembalian
  for i in money:
    j = 0
    #Buat loop jika kembalian lebih besar dari kembalian yang di loop
    while change >= i:
      #tambahkan jumlah kembalian dalam nominal yang di loop
      j += 1
      #kurangi kembalian dengan item yang diloop
      change -= i
      #isi nominal dan jumlah ke dictionary keterangan/ update
      dicts[i] = j
      #kembalikan looping ke awal
      continue
  #setelah semua item telah di loop dan tidak ada lagi nominal item yang
  #kurang dari kembalian
  #print keterangan kembalian
  for k, v in dicts.items():
    print("Uang Pecahan Rp."+ str(k) + " Sebanyak "+ str(v)+ " Lembar")

    

hitungUangKembalian(200000, 184600)