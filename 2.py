def cariNilaiTeratas(items):
  #musnahkan semua angka ganjil
  for i in items:
    if i % 2 == 0:
      items.remove(i)
  #hmmm ambil tiga item sort terbalik, tadaaa
  items = sorted(items, reverse=True)[:3]
  print("Nilai Tertinggi Pertama: " + str(items[0]))
  print("Nilai Tertinggi Kedua: " + str(items[1]))
  print("Nilai Tertinggi Ketiga: " + str(items[2]))

cariNilaiTeratas([1,4,6,2,6,8,9,21,20, 14, 3,6,11,1,1,2,3,4,6,8,9,2,1,5,2,5,6,8,3,2])
