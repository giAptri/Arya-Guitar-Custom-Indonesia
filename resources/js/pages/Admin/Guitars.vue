<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'; // Use useForm for easier file handling if possible, but axios is also fine. I'll stick to axios as per plan or use inertia form helper which is better for file uploads in Inertia.
// Actually, using Inertia's useForm is much better for file uploads and validation errors.
// However, the plan mentioned axios. I will use axios to avoid refactoring the whole page logic to Inertia if it was not using it before for data fetching.
// Wait, the page WAS using Inertia for rendering.
// Using axios for the API calls in a "SPA-like" way within the Inertia page is fine, but Inertia forms are easier.
// BUT, the existing code was just a static list.
// I will use axios as per plan to keep it simple and consistent with the "API" routes I saw (api.php).
// The routes in `routes/api.php` returns JSON Resources.
// If I use Inertia `router.visit`, it expects a full page response (Inertia response).
// Since the backend `GuitarController` is in `Api` namespace and returns `GuitarResource`, I MUST use axios. Inertia expects `Inertia::render` responses.

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
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

// Define Interface
interface Guitar {
    id: number;
    name: string;
    slug: string;
    short_desc: string | null;
    detail_desc: string | null;
    base_price: number | null;
    image_url: string | null;
    is_active: boolean;
}

const guitars = ref<Guitar[]>([]);
const searchQuery = ref('');
const showModal = ref(false);
const modalMode = ref<'add' | 'edit' | 'view'>('add');
const isLoading = ref(false);

// Form Data State
const form = ref({
    id: null as number | null,
    name: '',
    short_desc: '',
    detail_desc: '',
    base_price: null as number | null,
    is_active: true,
    image: null as File | null,
    remove_image: false // check if needed
});

const imagePreview = ref<string | null>(null);

const fetchGuitars = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/admin/data/guitars');
        // The API returns { data: [...] } due to Resource collection
        guitars.value = response.data.data; 
    } catch (error) {
        console.error("Error fetching guitars:", error);
        alert("Gagal mengambil data gitar.");
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchGuitars();
});

const filteredGuitars = computed(() => {
    if (!searchQuery.value) return guitars.value;
    const lower = searchQuery.value.toLowerCase();
    return guitars.value.filter(g => 
        g.name.toLowerCase().includes(lower) ||
        (g.short_desc && g.short_desc.toLowerCase().includes(lower))
    );
});

const resetForm = () => {
    form.value = {
        id: null,
        name: '',
        short_desc: '',
        detail_desc: '',
        base_price: null,
        is_active: true,
        image: null,
        remove_image: false
    };
    imagePreview.value = null;
};

const openModal = (mode: 'add' | 'edit' | 'view', guitar?: Guitar) => {
    modalMode.value = mode;
    resetForm();
    if (guitar) {
        form.value.id = guitar.id;
        form.value.name = guitar.name;
        form.value.short_desc = guitar.short_desc || '';
        form.value.detail_desc = guitar.detail_desc || '';
        form.value.base_price = guitar.base_price;
        form.value.is_active = !!guitar.is_active;
        imagePreview.value = guitar.image_url;
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    resetForm();
};

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.value.image = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const saveGuitar = async () => {
    if (!form.value.name) {
        alert("Nama gitar wajib diisi!");
        return;
    }

    isLoading.value = true;
    const formData = new FormData();
    formData.append('name', form.value.name);
    if (form.value.short_desc) formData.append('short_desc', form.value.short_desc);
    if (form.value.detail_desc) formData.append('detail_desc', form.value.detail_desc);
    if (form.value.base_price) formData.append('base_price', String(form.value.base_price));
    formData.append('is_active', form.value.is_active ? '1' : '0');
    
    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    try {
        if (modalMode.value === 'add') {
            await axios.post('/admin/data/guitars', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            alert("Gitar berhasil ditambahkan!");
        } else if (modalMode.value === 'edit' && form.value.id) {
            formData.append('_method', 'PUT'); // Laravel spoofing for PUT with files
            await axios.post(`/admin/data/guitars/${form.value.id}`, formData, {
                 headers: { 'Content-Type': 'multipart/form-data' }
            });
            alert("Gitar berhasil diperbarui!");
        }
        closeModal();
        fetchGuitars();
    } catch (error: any) {
        console.error("Error saving guitar:", error);
        alert("Gagal menyimpan data: " + (error.response?.data?.message || error.message));
    } finally {
        isLoading.value = false;
    }
};

const handleDelete = async (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus gitar ini?')) {
        isLoading.value = true;
        try {
            await axios.delete(`/admin/data/guitars/${id}`);
            alert("Gitar berhasil dihapus!");
            fetchGuitars();
        } catch (error) {
             console.error("Error deleting guitar:", error);
             alert("Gagal menghapus gitar.");
        } finally {
            isLoading.value = false;
        }
    }
};

const formatCurrency = (value: number | null) => {
    if (!value) return '-';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
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
                            <option>10</option>
                            <option>20</option>
                            <option>50</option>
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
                                <th class="px-8 py-4 border-r border-white/20">Harga Dasar</th>
                                <th class="px-8 py-4 border-r border-white/20">Deskripsi Singkat</th>
                                <th class="px-8 py-4 border-r border-white/20">Status</th>
                                <th class="px-8 py-4 border-r border-white/20">Foto</th>
                                <th class="px-8 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="isLoading" class="text-center">
                                <td colspan="6" class="py-8 text-gray-400">Loading data...</td>
                            </tr>
                            <tr v-else-if="filteredGuitars.length === 0" class="text-center">
                                <td colspan="6" class="py-8 text-gray-400">Belum ada data gitar.</td>
                            </tr>
                            <tr v-for="guitar in filteredGuitars" :key="guitar.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-black border-r border-gray-50">{{ guitar.name }}</td>
                                <td class="px-8 py-6 font-medium text-black border-r border-gray-50 whitespace-nowrap">{{ formatCurrency(guitar.base_price) }}</td>
                                <td class="px-8 py-6 text-sm text-gray-500 max-w-xs truncate border-r border-gray-50">{{ guitar.short_desc }}</td>
                                <td class="px-8 py-6 border-r border-gray-50">
                                    <span :class="{'bg-green-100 text-green-700': guitar.is_active, 'bg-red-100 text-red-700': !guitar.is_active}" class="px-2 py-1 rounded text-xs font-bold uppercase">
                                        {{ guitar.is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 border-r border-gray-50">
                                    <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden relative">
                                        <img v-if="guitar.image_url" :src="guitar.image_url" class="h-full w-full object-cover" alt="Foto" />
                                        <ImageIcon v-else class="h-5 w-5" />
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
                                v-model="form.name"
                                :disabled="modalMode === 'view'"
                                type="text" 
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-black focus:ring-2 focus:ring-arya-gold/20"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                             <div class="space-y-2">
                                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Harga Dasar (Rp)</label>
                                <input 
                                    v-model="form.base_price"
                                    :disabled="modalMode === 'view'"
                                    type="number" 
                                    placeholder="Contoh: 3500000"
                                    class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-black focus:ring-2 focus:ring-arya-gold/20"
                                />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Foto Gitar</label>
                                <div class="flex items-center gap-4">
                                     <div v-if="modalMode !== 'view'" class="w-full">
                                        <input 
                                            type="file" 
                                            @change="handleImageUpload" 
                                            accept="image/*"
                                            class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200"
                                        />
                                    </div>
                                    <div class="h-40 w-40 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border border-gray-200 shrink-0">
                                        <img v-if="imagePreview" :src="imagePreview" class="h-full w-full object-cover" />
                                        <ImageIcon v-else class="h-10 w-10 text-gray-300" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Deskripsi Singkat</label>
                            <input 
                                v-model="form.short_desc"
                                :disabled="modalMode === 'view'"
                                type="text" 
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-black focus:ring-2 focus:ring-arya-gold/20"
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Detail Deskripsi (Spesifikasi)</label>
                            <textarea 
                                v-model="form.detail_desc"
                                :disabled="modalMode === 'view'"
                                rows="4"
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-black focus:ring-2 focus:ring-arya-gold/20 resize-none"
                            ></textarea>
                        </div>

                        <div v-if="modalMode !== 'view'" class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                id="is_active" 
                                v-model="form.is_active" 
                                class="rounded text-arya-gold focus:ring-arya-gold/20"
                            />
                            <label for="is_active" class="text-sm font-bold text-gray-600">Status Aktif (Tampilkan di Katalog)</label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div v-if="modalMode !== 'view'" class="px-8 py-6 border-t border-gray-50 flex gap-4">
                    <Button @click="closeModal" variant="outline" class="flex-1 border-gray-100 py-6 font-bold" :disabled="isLoading">Batal</Button>
                    <Button @click="saveGuitar" class="flex-1 bg-arya-gold text-white py-6 font-bold hover:bg-arya-gold/90" :disabled="isLoading">
                        {{ isLoading ? 'Menyimpan...' : (modalMode === 'add' ? 'Simpan Gitar' : 'Perbarui Data') }}
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
