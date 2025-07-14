import * as bip39 from 'bip39';
import * as bip32 from 'bip32';
import * as bitcoin from 'bitcoinjs-lib';
import { keccak256 } from 'ethereumjs-util';
import { getAddress } from '@ethersproject/address';

export type SupportedCurrency = 'BTC' | 'ETH' | 'USDT' | 'LTC' | 'BCH';

export type GeneratedWallet = {
    name: string;
    currency: SupportedCurrency;
    address: string;
    privateKey: string;
    mnemonic: string;
};

export function generateWallet(name: string, currency: SupportedCurrency): GeneratedWallet {
    const mnemonic = bip39.generateMnemonic();
    const seed = bip39.mnemonicToSeedSync(mnemonic);
    const root = bip32.fromSeed(seed);

    let address = '';
    let privateKey = '';

    switch (currency) {
        case 'BTC': {
            const path = "m/44'/0'/0'/0/0";
            const child = root.derivePath(path);
            address = bitcoin.payments.p2pkh({
                pubkey: child.publicKey,
                network: bitcoin.networks.bitcoin,
            }).address!;
            privateKey = child.toWIF();
            break;
        }
        case 'ETH':
        case 'USDT': {
            const path = "m/44'/60'/0'/0/0";
            const child = root.derivePath(path);
            const ethPubKey = child.publicKey;
            const addressBuffer = keccak256(ethPubKey.slice(1)).slice(-20);
            address = getAddress('0x' + addressBuffer.toString('hex'));
            privateKey = '0x' + child.privateKey!.toString('hex');
            break;
        }
        case 'LTC': {
            const path = "m/44'/2'/0'/0/0";
            const child = root.derivePath(path);
            address = bitcoin.payments.p2pkh({
                pubkey: child.publicKey,
                network: {
                    ...bitcoin.networks.bitcoin,
                    messagePrefix: '\x19Litecoin Signed Message:\n',
                    bech32: 'ltc',
                    bip32: {
                        public: 0x019da462,
                        private: 0x019d9cfe,
                    },
                    pubKeyHash: 0x30,
                    scriptHash: 0x32,
                    wif: 0xb0,
                },
            }).address!;
            privateKey = child.toWIF();
            break;
        }
        case 'BCH': {
            const path = "m/44'/145'/0'/0/0";
            const child = root.derivePath(path);
            address = bitcoin.payments.p2pkh({
                pubkey: child.publicKey,
                network: {
                    ...bitcoin.networks.bitcoin,
                    pubKeyHash: 0x00,
                    scriptHash: 0x05,
                },
            }).address!;
            privateKey = child.toWIF();
            break;
        }
        default:
            throw new Error(`Unsupported currency: ${currency}`);
    }

    return {
        name,
        currency,
        address,
        privateKey,
        mnemonic,
    };
}
