import { DirectiveBinding } from 'vue';

type MaskType = 'cpf' | 'cnpj' | 'phone' | 'money';

export default {
	mounted(el: HTMLElement, binding: DirectiveBinding<MaskType>) {
		const formatValue = (value: string): string => {
			if (!value) return '';

			value = value.replace(/\D/g, '');

			if (binding.value === 'cpf' && value.length <= 11) {
				return value.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, (_, p1, p2, p3, p4) =>
					`${p1}.${p2}.${p3}${p4 ? '-' + p4 : ''}`
				);
			} else if (binding.value === 'cnpj' && value.length > 11) {
				return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2})/, (_, p1, p2, p3, p4, p5) =>
					`${p1}.${p2}.${p3}/${p4}${p5 ? '-' + p5 : ''}`
				);
			} else if (binding.value === 'phone' && value.length > 10) {
				return value
					.replace(/\D/g, '')
					.replace(/(\d{2})(\d)/, '($1) $2')
					.replace(/(\d{4,5})(\d{4})$/, '$1-$2');
			} else if (binding.value === 'money') {
				let clean = value.replace(/\D/g, '');

				if (clean.length < 3) {
					clean = clean.padStart(3, '0');
				}

				const cents = clean.slice(-2);
				const reais = clean.slice(0, -2).replace(/^0+/, '');

				const formattedReais = reais.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

				return formattedReais + ',' + cents;
			}

			return value;
		};

		const handleInput = (event: Event) => {
			const target = event.target as HTMLInputElement;
			const formatted = formatValue(target.value);
			target.value = formatted;

			// Gatilho para v-model
			target.dispatchEvent(new Event('input'));
		};

		el.addEventListener('input', handleInput);
	},
};
