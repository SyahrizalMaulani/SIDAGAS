import pika
import json
import logging
from .config import settings

logger = logging.getLogger(__name__)

def publish_event(routing_key: str, data: dict):
    try:
        params = pika.URLParameters(settings.RABBITMQ_URL)
        connection = pika.BlockingConnection(params)
        channel = connection.channel()
        
        # Publish to the topic exchange sidagas.events
        channel.basic_publish(
            exchange='sidagas.events',
            routing_key=routing_key,
            body=json.dumps(data),
            properties=pika.BasicProperties(
                content_type='application/json',
                delivery_mode=2  # persistent
            )
        )
        logger.info(f"Successfully published event {routing_key} with payload: {data}")
        connection.close()
    except Exception as e:
        logger.error(f"Failed to publish event {routing_key} to RabbitMQ: {str(e)}")
        # In a real production system, you would push this message to an outbox table or retry queue.
        # For development stability, we log the error and continue.
