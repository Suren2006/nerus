import mysql.connector
import asyncio
import websockets
import datetime

response_headers = {
    "Access-Control-Allow-Origin": "https://nerus",
}
connection = mysql.connector.connect(host="localhost", user="mysql", passwd="mysql", database="project")
cursor = connection.cursor()
connected_clients = set()
async def handle_client(websocket, path):
    connected_clients.add(websocket)
    print(connected_clients)
    
    try:
        async for message in websocket:
            messageArr = message.split(',')
            now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            # print("Received message:", messageArr[0])
            cursor.execute(f"INSERT INTO chat (message, from_user, to_user, send_on) VALUES ('{messageArr[0]}', '{messageArr[1]}', '{messageArr[2]}', '{now}')")
            connection.commit()
            # print("Message inserted into the database")
            # Echo the received message to all connected clients
            # for client in connected_clients:
            data =  websocket.recv()
            print(data)
            for client in connected_clients:
                await client.send(messageArr[0])
    finally:
        connected_clients.remove(websocket)

async def main():
    server = await websockets.serve(handle_client, 'nerus', 8765)
    await server.wait_closed()

asyncio.run(main())

# import mysql.connector
# import asyncio
# import websockets
# import datetime

# response_headers = {
#     "Access-Control-Allow-Origin": "https://nerus",
#     # Other WebSocket-related headers
# }



# connection = mysql.connector.connect(host="localhost", user="mysql", passwd="mysql", database="project")
# cursor = connection.cursor()



# connected_clients = set()


# async def handle_client():
    
#     uri = "ws://nerus:8765"
#     async with websockets.connect(uri) as websocket:
#         connected_clients.add(websocket)
#         print(connected_clients)
#         try:
#             async for message in websocket:
#                 messageArr = message.split(',')
#                 now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
#                 cursor.execute(f"INSERT INTO chat (message, from_user, to_user, send_on) VALUES ('{messageArr[0]}', '{messageArr[1]}', '{messageArr[2]}', '{now}')")
#                 connection.commit()
#                 for client in connected_clients:    
#                     await client.send(messageArr[0])
#         finally:
#             connected_clients.remove(websocket)

# # async def main():
# #     server = await websockets.serve(handle_client, "161.97.100.113", 8765)
# #     await server.wait_closed()

# # asyncio.run(main())


# asyncio.get_event_loop().run_until_complete(handle_client())

