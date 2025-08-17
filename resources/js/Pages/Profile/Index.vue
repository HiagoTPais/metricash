<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';
import ProfileForm from './Form.vue';
import MainLayout from "@/Layouts/MainLayout.vue";

type UserPayload = {
	id: number;
	name: string;
	email: string;
	created_at: string;
	status?: string | null;
	last_login_at?: string | null;
};

const props = defineProps<{ user: UserPayload | null; status?: string | null }>();

const isEditing = ref(false);
const page = usePage();
const flashSuccess = computed(() => (page.props as any).flash?.success);
const flashError = computed(() => (page.props as any).flash?.error);

function openEdit() {
	isEditing.value = true;
}

function closeEdit() {
	isEditing.value = false;
}
</script>

<template>
	<MainLayout>
			<div class="py-12">
				<div class="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
					<div v-if="flashSuccess" class="rounded-md border border-green-200 bg-green-50 p-3 text-green-700">
						{{ flashSuccess }}
					</div>
					<div v-if="flashError" class="rounded-md border border-red-200 bg-red-50 p-3 text-red-700">
						{{ flashError }}
					</div>

					<div class="bg-white p-6 shadow sm:rounded-lg">
						<div class="flex items-start justify-between">
							<div>
								<h3 class="text-lg font-medium text-gray-900">Account Information</h3>
								<p class="mt-1 text-sm text-gray-500">View your profile details.</p>
							</div>
							<PrimaryButton type="button" @click="openEdit">Edit Profile</PrimaryButton>
						</div>

						<dl class="mt-6 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
							<div>
								<dt class="text-sm font-medium text-gray-500">Name</dt>
								<dd class="mt-1 text-sm text-gray-900">{{ props.user?.name }}</dd>
							</div>
							<div>
								<dt class="text-sm font-medium text-gray-500">Email</dt>
								<dd class="mt-1 text-sm text-gray-900">{{ props.user?.email }}</dd>
							</div>
							<div>
								<dt class="text-sm font-medium text-gray-500">Status</dt>
								<dd class="mt-1 text-sm text-gray-900">{{ props.user?.status ?? 'Active' }}</dd>
							</div>
							<div>
								<dt class="text-sm font-medium text-gray-500">Member since</dt>
								<dd class="mt-1 text-sm text-gray-900">{{ props.user?.created_at ? new
									Date(props.user.created_at).toLocaleString() : '—' }}</dd>
							</div>
							<div>
								<dt class="text-sm font-medium text-gray-500">Last login</dt>
								<dd class="mt-1 text-sm text-gray-900">{{ props.user?.last_login_at ? new
									Date(props.user.last_login_at).toLocaleString() : 'N/A' }}</dd>
							</div>
						</dl>
					</div>
				</div>
			</div>

			<Modal :show="isEditing" @close="closeEdit">
				<div class="p-6">
					<h3 class="text-lg font-medium text-gray-900">Edit Profile</h3>
					<p class="mt-1 text-sm text-gray-500">Update your account details below.</p>
					<div class="mt-6">
						<ProfileForm :initial-name="props.user?.name || ''" :initial-email="props.user?.email || ''"
							@saved="closeEdit" />
					</div>
				</div>
			</Modal>
	</MainLayout>
</template>
