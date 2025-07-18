import * as bip39 from 'bip39';
import BIP32Factory from 'bip32';
import { BIP32Interface } from 'bip32';
import * as bitcoin from 'bitcoinjs-lib';
import { keccak256 } from 'ethereumjs-util';
import { getAddress } from '@ethersproject/address';
import * as ecc from 'tiny-secp256k1';

export type SupportedCurrency = 'BTC' | 'ETH' | 'USDT' | 'LTC' | 'BCH';

export type GeneratedWallet = {
    name: string;
    currency: SupportedCurrency;
    address: string;
    privateKey: string;
    mnemonic: string;
};

export function generateWallet(name: string, currency: string): GeneratedWallet {
    const mnemonic = bip39.generateMnemonic();
    const seed = bip39.mnemonicToSeedSync(mnemonic);
    const bip32 = BIP32Factory(ecc);
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
