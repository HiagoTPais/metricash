<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, computed, watch } from 'vue';

const props = defineProps<{ initialName: string; initialEmail: string }>();
const emit = defineEmits<{ (e: 'saved'): void }>();

const form = useForm({
	name: props.initialName,
	email: props.initialEmail,
	password: '',
	password_confirmation: '',
	profile_picture: null as File | null,
});

const hasFile = computed(() => !!form.profile_picture);
const previewUrl = ref<string | null>(null);

watch(
	() => form.profile_picture,
	(file) => {
		if (file instanceof File) {
			const reader = new FileReader();
			reader.onload = () => (previewUrl.value = reader.result as string);
			reader.readAsDataURL(file);
		} else {
			previewUrl.value = null;
		}
	}
);

function submit() {
	form.patch('/profile', {
		forceFormData: hasFile.value,
		onSuccess: () => {
			form.reset('password', 'password_confirmation');
			emit('saved');
		},
	});
}

function onFileChange(e: Event) {
	const target = e.target as HTMLInputElement;
	const file = target.files?.[0] || null;
	form.profile_picture = file as any;
}
</script>

<template>
	<form @submit.prevent="submit" class="space-y-6">
		<div>
			<InputLabel for="name" value="Name" />
			<TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
			<InputError class="mt-2" :message="form.errors.name" />
		</div>

		<div>
			<InputLabel for="email" value="Email" />
			<TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
			<InputError class="mt-2" :message="form.errors.email" />
		</div>

		<div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
			<div>
				<InputLabel for="password" value="New Password (optional)" />
				<TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" autocomplete="new-password" />
				<InputError class="mt-2" :message="form.errors.password" />
			</div>
			<div>
				<InputLabel for="password_confirmation" value="Confirm Password" />
				<TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" autocomplete="new-password" />
			</div>
		</div>

		<div>
			<InputLabel for="profile_picture" value="Profile Picture (optional)" />
			<input id="profile_picture" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100" @change="onFileChange" />
			<div v-if="previewUrl" class="mt-3">
				<img :src="previewUrl" alt="Preview" class="h-20 w-20 rounded-full object-cover shadow" />
			</div>
		</div>

		<div class="flex items-center justify-end gap-3">
			<SecondaryButton type="button" @click="$emit('saved')">Cancel</SecondaryButton>
			<PrimaryButton :disabled="form.processing">
				<span v-if="!form.processing">Save Changes</span>
				<span v-else>Saving...</span>
			</PrimaryButton>
		</div>
	</form>
</template>

