<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Button } from '@/components/ui/button';
import { 
    Plus, 
    Search, 
    Eye, 
    Pencil, 
    Trash2, 
    Image as ImageIcon,
    X,
    Upload
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

const searchQuery = ref('');
const showModal = ref(false);
const modalMode = ref<'add' | 'edit' | 'view'>('add');

const guitars = ref([
    { id: 1, name: 'Arya Stratosphere', short_desc: 'Gitar elektrik premium asli Indonesia', detail_desc: 'Detail deskripsi mendalam tentang material mahogany dan pickup custom...', price_range: '3.5M - 5M', image: null },
    { id: 2, name: 'Arya Flame', short_desc: 'Gitar paling populer untuk blues', detail_desc: 'Body alder dengan finishing sunburst yang elegan...', price_range: '2M - 3.5M', image: null },
    { id: 3, name: 'Arya Sahaya', short_desc: 'Modern look dengan material terbaik', detail_desc: 'Maple neck dengan pengerjaan CNC presisi...', price_range: '3M - 4.5M', image: null },
    { id: 4, name: 'Arya Bloom', short_desc: 'Akustik desain khusus pemula', detail_desc: 'Suara jernih dan action rendah untuk kenyamanan jari...', price_range: '1M - 2.5M', image: null },
]);

const filteredGuitars = computed(() => {
    return guitars.value.filter(g => 
        g.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        g.short_desc.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const currentGuitar = ref({
    name: '',
    short_desc: '',
    detail_desc: '',
    price_range: '',
    image: null
});

const openModal = (mode: 'add' | 'edit' | 'view', guitar?: any) => {
    modalMode.value = mode;
    if (guitar) {
        currentGuitar.value = { ...guitar };
    } else {
        currentGuitar.value = { name: '', short_desc: '', detail_desc: '', price_range: '', image: null };
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleDelete = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus gitar ini?')) {
        guitars.value = guitars.value.filter(g => g.id !== id);
    }
};
</script>

<template>
    <Head title="Kelola Gitar" />

    <AdminLayout>
        <div class="mt-8">
            <h1 class="text-3xl font-black text-black text-center">
                Kelola <span class="text-arya-gold">gitar</span> mu sekarang!
            </h1>

            <div class="mt-12 bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50 overflow-hidden">
                <!-- Toolbar -->
                <div class="p-8 flex items-center justify-between border-b border-gray-50 gap-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-400">
                        <span>Show</span>
                        <select class="bg-gray-50 border-none rounded-lg px-2 py-1 text-black focus:ring-0">
                            <option>7</option>
                            <option>10</option>
                            <option>20</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="flex items-center gap-4 flex-1 max-w-2xl px-8">
                        <div class="relative w-full">
                            <Search class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-300" />
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search..." 
                                class="w-full bg-gray-50 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20 transition-all"
                            />
                        </div>
                    </div>

                    <Button @click="openModal('add')" class="bg-[#2d5a7d] hover:bg-[#254a68] text-white px-6 py-6 rounded-xl font-bold gap-2">
                        <Plus class="h-5 w-5" />
                        Tambah
                    </Button>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-arya-gold text-white uppercase text-[10px] font-black tracking-widest">
                                <th class="px-8 py-4 border-r border-white/20">Nama Gitar</th>
                                <th class="px-8 py-4 border-r border-white/20">Deskripsi Singkat</th>
                                <th class="px-8 py-4 border-r border-white/20">Foto</th>
                                <th class="px-8 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="guitar in filteredGuitars" :key="guitar.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-black border-r border-gray-50">{{ guitar.name }}</td>
                                <td class="px-8 py-6 text-sm text-gray-500 max-w-xs truncate border-r border-gray-50">{{ guitar.short_desc }}</td>
                                <td class="px-8 py-6 border-r border-gray-50">
                                    <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                        <ImageIcon class="h-5 w-5" />
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <button @click="openModal('view', guitar)" class="text-blue-500 hover:scale-110 transition-transform" title="View Detail">
                                            <Eye class="h-5 w-5" />
                                        </button>
                                        <button @click="openModal('edit', guitar)" class="text-green-500 hover:scale-110 transition-transform" title="Edit">
                                            <Pencil class="h-5 w-5" />
                                        </button>
                                        <button @click="handleDelete(guitar.id)" class="text-red-500 hover:scale-110 transition-transform" title="Delete">
                                            <Trash2 class="h-5 w-5" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CRUD Modal -->
        <div v-if="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeModal"></div>
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <!-- Modal Header -->
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-arya-gold">
                    <h2 class="text-xl font-black text-white uppercase tracking-tight">
                        {{ modalMode === 'add' ? 'Tambah Gitar Baru' : (modalMode === 'edit' ? 'Edit Data Gitar' : 'Detail Gitar') }}
                    </h2>
                    <button @click="closeModal" class="text-white hover:rotate-90 transition-transform">
                        <X class="h-6 w-6" />
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <div class="grid gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Nama Gitar</label>
                            <input 
                                v-model="currentGuitar.name"
                                :disabled="modalMode === 'view'"
                                type="text" 
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                             <div class="space-y-2">
                                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Range Harga</label>
                                <input 
                                    v-model="currentGuitar.price_range"
                                    :disabled="modalMode === 'view'"
                                    type="text" 
                                    placeholder="Contoh: 2M - 3M"
                                    class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Foto Gitar</label>
                                <div class="flex items-center gap-4">
                                    <Button v-if="modalMode !== 'view'" variant="outline" class="w-full border-dashed border-gray-200 text-gray-400 text-xs">
                                        <Upload class="h-4 w-4 mr-2" /> Upload
                                    </Button>
                                    <div v-else class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <ImageIcon class="h-5 w-5 text-gray-300" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Deskripsi Singkat</label>
                            <input 
                                v-model="currentGuitar.short_desc"
                                :disabled="modalMode === 'view'"
                                type="text" 
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20"
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Detail Deskripsi (Spesifikasi)</label>
                            <textarea 
                                v-model="currentGuitar.detail_desc"
                                :disabled="modalMode === 'view'"
                                rows="4"
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20 resize-none"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div v-if="modalMode !== 'view'" class="px-8 py-6 border-t border-gray-50 flex gap-4">
                    <Button @click="closeModal" variant="outline" class="flex-1 border-gray-100 py-6 font-bold">Batal</Button>
                    <Button class="flex-1 bg-arya-gold text-white py-6 font-bold hover:bg-arya-gold/90">
                        {{ modalMode === 'add' ? 'Simpan Gitar' : 'Perbarui Data' }}
                    </Button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Scoped styles for table and modal */
select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='C19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    background-size: 1em;
}
</style>
