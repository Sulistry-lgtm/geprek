<x-mail::message>
# Ada Pesan Kontak Baru

Seseorang telah mengirimkan pesan melalui formulir kontak BestieGeprek:

* **Nama Pengirim:** {{ $name }}
* **Email Pengirim:** {{ $email }}

---

### **Isi Pesan:**
{{ $body }}

---

Terima kasih,<br>
Sistem {{ config('app.name') }}
</x-mail::message>
