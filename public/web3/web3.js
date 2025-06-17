let web3 = new Web3("https://polygon-rpc.com/")

let chainId = 137

let token_address = "0xc2132d05d31c914a87c6611c10748aeb04b58e8f";

let tokenInst;

let tokenABI = `[{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"inputs":[{"internalType":"address","name":"account","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"_burnFrom","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"renounceOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"}]`

try {
    if (ethereum != null) {
        if (parseInt(ethereum.chainId) === chainId) {
            web3 = new Web3(window.ethereum);
        }
        ethereum.on('chainChanged', function () {
            // doConnect();
        });
    }
} catch (e) {
    showToast("warning", 'Please install metamask wallet extension like metamask, trustwallet');
}

$(document).on("click", ".connectWallet", function () {
    doConnect()
});

function setText(ele, value) {
    $("." + ele).each((d, a) => {
        a.innerText = value
    })
}

function setHtml(ele, value) {
    $("." + ele).each((d, a) => {
        a.innerHTML = value
    })
}

function toSmall(value, deci = 18, fixed = 4) {
    return (value / Math.pow(10, deci)).toFixed(fixed);
}

function toBig(value, deci = 18) {
    return ethers.utils.parseUnits(value.toString(), deci)
}

function getPara(parameterName) {
    var result = null,
        tmp = [];
    var items = location.search.substr(1).split("&");
    for (var index = 0; index < items.length; index++) {
        tmp = items[index].split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
    }
    return result;
}

let explorer = "https://polygonscan.com/tx/";

let walletAddress;

async function doConnect() {
    try {
        if (web3 != null) {
            try {
                if (window.ethereum != null) {
                    var addressConnected = await window.ethereum.request({ method: "eth_requestAccounts" }, () => { console.log("connected") });
                    if (addressConnected) {

                        if (parseInt(window.ethereum.chainId) !== chainId) {
                            addOutNetwork()
                        }

                        walletAddress = addressConnected[0]

                        showToast('success', 'Wallet Connected Successfully')
                    }
                    else {
                        showToast("error", "Error While Connecting Wallet")
                    }
                }

                signer = (
                    window.ethereum ? (new ethers.providers.Web3Provider(window.ethereum)).getSigner()
                        : new ethers.providers.Web3Provider(web3.currentProvider)
                )

                if (token_address != "0x") {
                    tokenInst = new ethers.Contract(token_address, tokenABI, signer)
                }

                return walletAddress;

            } catch (err) {
                showToast("error", "Unhandled Error - " + err)
            }
        } else {
            showToast("info", 'Please install Web3 wallet extension like metamask, trustwallet');
        }
    } catch (e) {
        showToast("info", 'Please install Web3 wallet extension like metamask, trustwallet');
    }
}

async function addOutNetwork(){
  try {
        // check if the chain to connect to is installed
      await window.ethereum.request({
        method: 'wallet_switchEthereumChain',
        params: [{ chainId: '0x89' }], // chainId must be in hexadecimal numbers
      });

    } catch (error) {
      // This error code indicates that the chain has not been added to MetaMask
      // if it is not, then install it into the user MetaMask
      // alert(error.code == "-32603")
      // if (error.code === 4902 || error.code == "-32603") {
        try {
          await window.ethereum.request({
            method: 'wallet_addEthereumChain',
            params: [
              {
                chainId: '0x89',
                chainName: "Polygon",
                rpcUrls: ['https://polygon-rpc.com'],
                nativeCurrency: {
                    name: "MATIC",
                    symbol: "MATIC", // 2-6 characters long
                    decimals: 18
                },
              },
            ],
          });
          // alert("Chain Added!")
        } catch (addError) {
            // alert(JSON.stringify(addError));
          console.error(addError);
        }
      // }
      // alert(JSON.stringify(error));
      console.error(error);
    }
}

