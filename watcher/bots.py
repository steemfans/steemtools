#encoding:UTF-8
import json, os, time
import requests
from contextlib import suppress
from steem.blockchain import Blockchain
from steem.steemd import Steemd

def run():
    env_dist = os.environ
    api_url = env_dist.get('API_URL')
    env_block_num = env_dist.get('BLOCK_NUM')
    print(api_url)
    if api_url == None:
        print("Please set API_URL env")
        return
    steemd_nodes = [
        'https://rpc.buildteam.io',
        'https://api.steemit.com',
    ]
    s = Steemd(nodes=steemd_nodes)
    b = Blockchain(s)
    if env_block_num == None:
        block_num = 0
    else:
        block_num = int(env_block_num)
    while True:
        if block_num == 0:
            last_irreversible_block_num = b.info()['last_irreversible_block_num']
            block_num = int(last_irreversible_block_num)
        print('start at block number: {block_num}'.format(block_num=block_num))
        block_info = s.get_block(block_num)
        transactions = block_info['transactions']
        for trans in transactions:
            operations = trans['operations']
            for op in operations:
                if op[0] == 'comment' and op[1]['parent_author'] != '':
                    import requests
                    postdata = json.dumps(op[1])
                    r = requests.post(api_url, data=postdata)
                    print(r.text)
        block_num = block_num + 1
        time.sleep(3)

if __name__ == '__main__':
    with suppress(KeyboardInterrupt):
        run()
