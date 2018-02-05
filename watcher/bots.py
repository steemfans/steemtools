#encoding:UTF-8
import json, os, sys, time
import requests
from contextlib import suppress
from concurrent import futures
from steem.blockchain import Blockchain
from steem.steemd import Steemd


env_dist = os.environ
api_url = env_dist.get('API_URL')
if api_url == None:
    print("Please set API_URL env")
    sys.exit()
print(api_url)
worker_num = env_dist.get('WORKER_NUM')
if worker_num == None:
    worker_num = 5
print('Worker num: %s' % (worker_num))
worker_num = int(worker_num)
env_block_num = env_dist.get('BLOCK_NUM')
if env_block_num == None:
    start_block_num = 0
else:
    start_block_num = int(env_block_num)

steemd_nodes = [
    'https://rpc.buildteam.io',
    'https://api.steemit.com',
]
s = Steemd(nodes=steemd_nodes)
b = Blockchain(s)

def worker(start, end):
    global s, b
    print('start from {start} to {end}'.format(start=start, end=end))
    block_infos = s.get_blocks(range(start, end+1))
    # print(block_infos)
    for block_info in block_infos:
        transactions = block_info['transactions']
        for trans in transactions:
            operations = trans['operations']
            for op in operations:
                if op[0] == 'comment' and op[1]['parent_author'] != '':
                    postdata = json.dumps(op[1])
                    r = requests.post(api_url, data=postdata)
                    print('{start}:{end}: {result}'.format(
                        start=start,
                        end=end,
                        result=r.text)
                        )

def run():
    global start_block_num
    steemd_nodes = [
        'https://rpc.buildteam.io',
        'https://api.steemit.com',
    ]
    s = Steemd(nodes=steemd_nodes)
    b = Blockchain(s)

    while True:
        last_irreversible_block_num = b.info()['last_irreversible_block_num']
        end_block_num = int(last_irreversible_block_num)
        if start_block_num == 0:
            start_block_num = end_block_num - 3
        with futures.ThreadPoolExecutor(max_workers=worker_num) as executor:
            executor.submit(worker, start_block_num, end_block_num)
        start_block_num = end_block_num
        time.sleep(3)

if __name__ == '__main__':
    with suppress(KeyboardInterrupt):
        run()
